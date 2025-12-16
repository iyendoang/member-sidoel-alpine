<?php

   namespace App\Http\Controllers\Account;

   use App\Exports\CustomersExport;
   use App\Imports\CustomersImport;
   use App\Models\Outlet;
   use App\Models\Customer;
   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\HasMiddleware;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Support\Facades\DB;
   use Maatwebsite\Excel\Facades\Excel;
   use Illuminate\Support\Facades\Storage;

   class CustomerController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:customers.index'], only: ['index']),
            new Middleware(['permission:customers.create'], only: ['create', 'store']),
            new Middleware(['permission:customers.edit'], only: ['edit', 'update']),
            new Middleware(['permission:customers.delete'], only: ['destroy']),
         ];
      }

      /**
       * Halaman Tools Import/Export
       */
      public function importExportView()
      {
         $outlets = auth()->user()->can('admin') ? Outlet::all() : collect();
         return view('account.customers.preview-import', compact('outlets'));
      }

      /**
       * STEP 1: PREVIEW & STAGING DATA
       * Baca Excel -> Convert ke Array -> Simpan ke JSON Storage -> Tampilkan
       */
      public function previewImport(Request $request)
      {
         $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'outlet_id' => 'nullable|integer',
         ]);

         try {
            // 1. Baca Excel Langsung ke Array (Tanpa simpan file Excel fisik)
            // Kita gunakan Import Class hanya untuk struktur, tapi datanya kita ambil langsung
            $rawArray = Excel::toArray(new CustomersImport(
               auth()->user()->outlet_id,
               false // parameter dummy
            ), $request->file('file'));

         } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel. Pastikan format sesuai.');
         }

         // 2. Bersihkan & Format Data
         // Asumsi data ada di sheet 1 (index 0)
         $rows = collect($rawArray[0] ?? [])->filter(function ($row) {
            return !empty($row['email']) && !empty($row['name']); // Validasi dasar baris kosong
         });

         if ($rows->isEmpty()) {
            return back()->with('error', 'File Excel kosong atau tidak ada kolom email/name yang valid.');
         }

         // 3. Siapkan Data Matang untuk Preview & Staging
         $targetOutletId = $request->filled('outlet_id') ? (int) $request->outlet_id : auth()->user()->outlet_id;

         // Ambil email existing untuk cek UPDATE/INSERT
         $existingEmails = Customer::where('outlet_id', $targetOutletId)
                                   ->pluck('email')
                                   ->toArray();

         $processedData = $rows->map(function ($row) use ($existingEmails, $targetOutletId) {
            $email = trim($row['email']);
            $exists = in_array($email, $existingEmails);

            return [
               'outlet_id'   => $targetOutletId,
               'name'        => $row['name'],
               'email'       => $email,
               'office_name' => $row['office_name'] ?? null,
               'phone'       => $row['phone'] ?? null,
               'address'     => $row['address'] ?? null,
               'status'      => $exists ? 'UPDATE' : 'INSERT', // Flag untuk UI
               'row_class'   => $exists ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700'
            ];
         })->values()->toArray(); // Reset keys agar jadi clean array

         // 4. SIMPAN DATA KE FILE JSON (STAGING)
         // Kita simpan file berdasarkan User ID agar tidak tertukar antar user
         $stagingFilename = 'import_staging/user_' . auth()->id() . '.json';
         Storage::put($stagingFilename, json_encode($processedData));

         // 5. Tampilkan View
         $outlets = auth()->user()->can('admin') ? Outlet::all() : collect();

         return view('account.customers.preview-import', [
            'previewData' => collect($processedData), // Kirim sebagai collection ke View
            'stagingFile' => $stagingFilename,        // Kirim nama file JSON ke View
            'targetOutletId' => $targetOutletId,
            'outlets' => $outlets
         ]);
      }

      /**
       * STEP 2: PROCESS IMPORT DARI JSON
       * Baca JSON -> Chunk Loop -> Insert/Update DB
       */
      public function processImport(Request $request)
      {
         $request->validate([
            'staging_file' => 'required' // Nama file JSON yang dikirim dari form hidden
         ]);

         $stagingFile = $request->staging_file;

         // 1. Cek Apakah File Staging Ada
         if (!Storage::exists($stagingFile)) {
            return redirect()->route('account.customers.import-view')
                             ->with('error', 'Sesi import kadaluarsa. Silakan upload ulang file Excel.');
         }

         // 2. Baca Data dari JSON
         $jsonContent = Storage::get($stagingFile);
         $data = json_decode($jsonContent, true);

         if (empty($data)) {
            return redirect()->route('account.customers.import-view')
                             ->with('error', 'Data import kosong atau rusak.');
         }

         $successCount = 0;
         $errors = [];

         // 3. Proses Insert/Update dengan Chunking (Misal per 20 data)
         // Chunking membantu agar memori server tidak meledak jika datanya ribuan
         $chunks = array_chunk($data, 20);

         DB::beginTransaction(); // Pakai transaksi agar aman
         try {
            foreach ($chunks as $chunk) {
               foreach ($chunk as $row) {
                  try {
                     Customer::updateOrCreate(
                        [
                           'email'     => $row['email'],
                           'outlet_id' => $row['outlet_id']
                        ],
                        [
                           'name'        => $row['name'],
                           'office_name' => $row['office_name'],
                           'phone'       => $row['phone'],
                           'address'     => $row['address'],
                        ]
                     );
                     $successCount++;
                  } catch (\Exception $e) {
                     // Catat error tapi jangan hentikan loop (opsional)
                     $errors[] = "Gagal memproses email {$row['email']}: " . $e->getMessage();
                  }
               }
            }
            DB::commit(); // Simpan permanen jika lancar

            // 4. Hapus File Staging setelah sukses
            Storage::delete($stagingFile);

            // Redirect Sukses
            if (count($errors) > 0) {
               return redirect()->route('account.customers.import-view')
                                ->with('warning', "Import selesai: {$successCount} data masuk, " . count($errors) . " gagal.")
                                ->with('import_errors', $errors);
            }

            return redirect()->route('account.customers.import-view')
                             ->with('success', "Sukses! {$successCount} data berhasil diproses.");

         } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika error fatal
            return redirect()->route('account.customers.import-view')
                             ->with('error', 'Terjadi kesalahan sistem saat menyimpan database: ' . $e->getMessage());
         }
      }

      // =========================================================================
      // STANDARD RESOURCE METHODS (Index, Export, Create, Store, Edit, Update, Destroy)
      // =========================================================================

      public function index()
      {
         $customers = Customer::when(request()->search, function ($query) {
            $query->where(function ($q) {
               $q->where('name', 'like', '%' . request()->search . '%')
                 ->orWhere('office_name', 'like', '%' . request()->search . '%');
            });
         })->latest()->paginate(10);

         $customers->appends(['search' => request()->search]);

         $outlets = auth()->user()->can('admin') ? Outlet::orderBy('name')->get() : collect();

         return view('account.customers.index', compact('customers', 'outlets'));
      }

      public function export(Request $request)
      {
         $outletId = auth()->user()->can('admin') ? $request->outlet_id : null;

         return Excel::download(
            new CustomersExport($outletId),
            'customers_' . now()->format('Ymd_His') . '.xlsx'
         );
      }

      public function create()
      {
         $outlets = Outlet::all();
         return view('account.customers.create', compact('outlets'));
      }

      public function store(Request $request)
      {
         $request->validate([
            'name'        => 'required',
            'office_name' => 'required',
            'email'       => 'required',
            'phone'       => 'required',
            'address'     => 'required',
         ]);

         $outlet = auth()->user()->can('admin') ? $request->outlet_id : auth()->user()->outlet_id;

         Customer::create([
            'outlet_id'   => $outlet,
            'name'        => $request->name,
            'office_name' => $request->office_name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
         ]);

         return redirect()->route('account.customers.index')->with('success', 'Customer created successfully');
      }

      public function edit($id)
      {
         $customer = Customer::findOrFail($id);
         $outlets = Outlet::all();
         return view('account.customers.edit', compact('customer', 'outlets'));
      }

      public function update(Request $request, $id)
      {
         $customer = Customer::findOrFail($id);
         $request->validate([
            'name'        => 'required',
            'office_name' => 'required',
            'email'       => 'required',
            'phone'       => 'required',
            'address'     => 'required',
         ]);

         $outlet = auth()->user()->can('admin') ? $request->outlet_id : auth()->user()->outlet_id;

         $customer->update([
            'outlet_id'   => $outlet,
            'name'        => $request->name,
            'office_name' => $request->office_name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
         ]);

         return redirect()->route('account.customers.index')->with('success', 'Customer updated successfully');
      }

      public function destroy($id)
      {
         $customer = Customer::findOrFail($id);
         $customer->delete();
         return redirect()->route('account.customers.index')->with('success', 'Customer deleted successfully');
      }
   }