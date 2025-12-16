<?php

   namespace App\Http\Controllers\Account;

   use App\Models\Package;
   use App\Models\Customer;
   use App\Models\Transaction;
   use App\Models\Unit;
   use HTMLPurifier;
   use HTMLPurifier_Config;
   use Illuminate\Http\Request;
   use Barryvdh\DomPDF\Facade\Pdf;
   use Illuminate\Support\Facades\DB;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class TransactionController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       *
       * @return array
       */
      public static function middleware(): array {
         return [
            new Middleware(['permission:transactions.index'], only:['index']),
            new Middleware(['permission:transactions.create'], only:['create', 'store']),
            new Middleware(['permission:transactions.edit'], only:['edit', 'update']),
            new Middleware(['permission:transactions.delete'], only:['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index() {
         //get transactions with search
         $transactions = Transaction::byOutlet()->with('outlet')->when(request()->search, function($query) {
            $query->where('invoice', 'like', '%' . request()->search . '%');
         })->latest()->paginate(10);
         //append search param to pagination links
         $transactions->appends(['search' => request()->search]);

         //return view
         return view('account.transactions.index', compact('transactions'));
      }

      /**
       * create
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function create() {
         //get packages
         $query    = Package::with('category_package')
                            ->where('outlet_id', auth()->user()->outlet_id);
         $packages = $query->get()->map(function($package) {
            return [
               'id'    => $package->id,
               'name'  => $package->name,
               'type'  => $package->category_package->name ?? '-',
               'price' => (float) $package->price,
            ];
         });
         //get customers
         $customers = Customer::where('outlet_id', auth()->user()->outlet_id)->get();
         // units
         $units = Unit::orderBy('name')->get(['name']);

         return view('account.transactions.create', compact(
            'packages',
            'customers',
            'units'
         ));
      }

      /**
       * store
       *
       * @param mixed $request
       *
       * @return \Illuminate\Http\JsonResponse
       */
      public function store(Request $request)
      {
         try {
            DB::transaction(function () use ($request) {

               $invoice = 'INV-' . mt_rand(1000, 9999);

               $transaction = Transaction::create([
                  'invoice'          => $invoice,
                  'user_id'          => auth()->id(),
                  'outlet_id'        => auth()->user()->outlet_id,
                  'customer_id'      => $request->customer_id,
                  'date'             => $request->transaction_date,
                  'deadline'         => $request->due_date,
                  'status'           => $request->status ?? 'NEW',
                  'payment_status'   => $request->payment_status,
                  'payment_date'     => $request->payment_date,
                  'tax_percent'      => $request->tax_percent ?? 0,
                  'tax_amount'       => $request->tax_amount ?? 0,
                  'discount_percent' => $request->discount_percent ?? 0,
                  'discount_amount'  => $request->discount_amount ?? 0,
                  'additional_fee'   => $request->additional_fee ?? 0,
                  'total_price'      => $request->total_price,
                  'notes'            => $this->purifyNotes($request->notes),
               ]);

               foreach ($request->packages as $packageData) {
                  $package = Package::findOrFail($packageData['id']);

                  $transaction->transaction_details()->create([
                     'package_id' => $package->id,
                     'quantity'   => $packageData['qty'],
                     'unit'       => $packageData['unit'],
                     'total'      => $package->price * $packageData['qty'],
                  ]);
               }
            });

            return response()->json([
               'message' => 'Transaksi berhasil disimpan.',
            ], 201);

         } catch (\Throwable $e) {
            return response()->json([
               'message' => 'Gagal menyimpan transaksi.',
               'error'   => $e->getMessage(),
            ], 500);
         }
      }

      /**
       * edit
       *
       * @param mixed $id
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function edit($id) {
         // Find the transaction by ID
         $transaction = Transaction::byOutlet()->findOrFail($id);
         // Get packages for dropdown
         $query    = Package::with('category_package')
                            ->where('outlet_id', auth()->user()->outlet_id);
         $packages = $query->get()->map(function($package) {
            return [
               'id'    => $package->id,
               'name'  => $package->name,
               'type'  => $package->category_package->name ?? '-',
               'price' => (float) $package->price,
            ];
         });
         // Get customers
         $customers = Customer::byOutlet()->get();
         $units     = Unit::orderBy('name')->get(['name']);

         return view('account.transactions.edit', compact(
            'transaction',
            'packages',
            'customers',
            'units',
         ));
      }

      /**
       * update
       *
       * @param mixed $request
       * @param mixed $transaction
       *
       * @return \Illuminate\Http\JsonResponse
       */
      public function update(Request $request, Transaction $transaction) {
         try {
            DB::transaction(function() use ($request, $transaction) {
               $config   = HTMLPurifier_Config::createDefault();
               $purifier = new HTMLPurifier($config);
               $notes    = $purifier->purify($request->notes);
               // Update transaction data
               $transaction->update([
                  'customer_id'      => $request->customer_id,
                  'date'             => $request->transaction_date,
                  'deadline'         => $request->due_date,
                  'status'           => $request->status,
                  'payment_status'   => $request->payment_status,
                  'payment_date'     => $request->payment_date,
                  'tax_percent'      => $request->tax_percent ?? 0,
                  'tax_amount'       => $request->tax_amount ?? 0,
                  'discount_percent' => $request->discount_percent ?? 0,
                  'discount_amount'  => $request->discount_amount ?? 0,
                  'additional_fee'   => $request->additional_fee ?? 0,
                  'total_price'      => $request->total_price,
                  'notes'            => $notes ?? NULL,
               ]);
               // Delete existing transaction details
               $transaction->transaction_details()->delete();
               // Add new transaction details
               foreach($request->packages as $packageData) {
                  $package = Package::findOrFail($packageData['id']);
                  $transaction->transaction_details()->create([
                     'package_id' => $package->id,
                     'quantity'   => $packageData['qty'],
                     'unit'       => $packageData['unit'],
                     'total'      => $package->price*$packageData['qty'],
                  ]);
               }
            });

            return response()->json([
               'message' => 'Transaksi berhasil diperbarui.',
            ], 200);
         } catch(\Throwable $e) {
            return response()->json([
               'message' => 'Gagal memperbarui transaksi.',
               'error'   => $e->getMessage(),
            ], 500);
         }
      }

      /**
       * print
       *
       * @param mixed $id
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
//            public function print($id) {
//               //get transaction
//               $transaction = Transaction::with('outlet', 'customer', 'transaction_details.package')->findOrFail($id);
//               //check if request type is regular or thermal
//               if(request('type') == 'regular'){
//                  //generate pdf
//                  $pdf = Pdf::loadView('account.transactions.print.reguler', compact('transaction'));
//               }
//               else {
//                  //generate thermal
//                  return view('account.transactions.print.thermal', compact('transaction'));
//               }
//
//               //return
//               return $pdf->download('receipt.pdf');
//            }
      public function print($id) {
         // Get transaction
         $transaction = Transaction::with('outlet', 'customer', 'transaction_details.package')->findOrFail($id);
         // Check if request type is regular or thermal
         if(request('type') == 'regular'){
            // Generate PDF
            $pdf = Pdf::loadView('account.transactions.print.reguler', compact('transaction'));

            // Stream PDF to browser (preview) instead of forcing download
            return $pdf->stream('receipt.pdf'); // <-- ini menampilkan PDF di browser
         }
         else {
            // Thermal view (HTML)
            return view('account.transactions.print.thermal', compact('transaction'));
         }
      }

      /**
       * destroy
       *
       * @param mixed $id
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function destroy($id) {
         //get transaction
         $transaction = Transaction::findOrFail($id);
         //delete customer
         $transaction->delete();

         //redirect
         return redirect()->route('account.transactions.index')->with('success', 'Transaction deleted successfully');
      }
      protected function purifyNotes(?string $notes): ?string
      {
         if (!$notes) {
            return null;
         }

         $config = HTMLPurifier_Config::createDefault();

         // ⬅️ PENTING: arahkan cache ke storage
         $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));

         // (opsional) batasi HTML yang boleh
         // $config->set('HTML.Allowed', 'p,br,strong,em,ul,ol,li');

         return (new HTMLPurifier($config))->purify($notes);
      }

   }
