<?php

   namespace App\Http\Controllers\Account;

   use App\Models\Outlet;
   use App\Models\Package;
   use Illuminate\Http\Request;
   use App\Models\CategoryPackage;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class PackageController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:packages.index'], only: ['index']),
            new Middleware(['permission:packages.create'], only: ['create', 'store']),
            new Middleware(['permission:packages.edit'], only: ['edit', 'update']),
            new Middleware(['permission:packages.delete'], only: ['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index()
      {
         // Ambil data package dengan relasi outlet dan category_package
         $packages = Package::byOutlet()->with(['outlet', 'category_package'])
                            ->when(request()->search, function ($query) {
                               $query->where('name', 'like', '%' . request()->search . '%');
                            })
                            ->latest()
                            ->paginate(10);

         // Tambahkan parameter search ke pagination
         $packages->appends(['search' => request()->search]);

         // Kirim data ke view
         return view('account.packages.index', compact('packages'));
      }

      /**
       * create
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function create()
      {
         // Ambil semua data outlet
         $outlets = Outlet::all();

         // Ambil semua kategori paket
         $categories = CategoryPackage::all();

         // Kirim data ke view
         return view('account.packages.create', compact('outlets', 'categories'));
      }

      /**
       * store
       *
       * @param  mixed $request
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function store(Request $request)
      {
         // Validasi input
         $request->validate([
            'category_package_id' => 'required|exists:category_packages,id',
            'name'                => 'required|string|max:255',
            'price'               => 'required|numeric|min:0',
         ]);

         //check is admin
         if(auth()->user()->can('admin')) {
            $outlet = $request->outlet_id;
         } else {
            $outlet = auth()->user()->outlet_id;
         }

         // Simpan data ke database
         Package::create([
            'outlet_id'           => $outlet,
            'category_package_id' => $request->category_package_id,
            'name'                => $request->name,
            'price'               => $request->price
         ]);

         // Redirect dengan pesan sukses
         return redirect()->route('account.packages.index')->with('success', 'Package created successfully');
      }

      /**
       * edit
       *
       * @param  mixed $package
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function edit($id)
      {
         // Ambil data package berdasarkan ID
         $package = Package::findOrFail($id);

         // Ambil outlet
         $outlets = Outlet::all();

         // Ambil kategori paket
         $categories = CategoryPackage::all();

         // Kirim data ke view
         return view('account.packages.edit', compact('package', 'outlets', 'categories'));
      }

      /**
       * update
       *
       * @param  mixed $request
       * @param  mixed $package
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function update(Request $request, Package $package)
      {
         // Validasi input
         $request->validate([
            'category_package_id' => 'required|exists:category_packages,id',
            'name'                => 'required|string|max:255',
            'price'               => 'required|numeric|min:0',
         ]);

         //check is admin
         if(auth()->user()->can('admin')) {
            $outlet = $request->outlet_id;
         } else {
            $outlet = auth()->user()->outlet_id;
         }

         // Update data di database
         $package->update([
            'outlet_id'           => $outlet,
            'category_package_id' => $request->category_package_id,
            'name'                => $request->name,
            'price'               => $request->price
         ]);

         // Redirect dengan pesan sukses
         return redirect()->route('account.packages.index')->with('success', 'Package updated successfully');
      }

      /**
       * destroy
       *
       * @param  mixed $id
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function destroy($id)
      {
         // Ambil data package berdasarkan ID
         $package = Package::findOrFail($id);

         // Hapus data dari database
         $package->delete();

         // Redirect dengan pesan sukses
         return redirect()->route('account.packages.index')->with('success', 'Package deleted successfully');
      }
   }
