<?php

   namespace App\Http\Controllers\Account;

   use Illuminate\Http\Request;
   use App\Models\CategoryPackage;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class CategoryPackageController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:category-packages.index'], only: ['index']),
            new Middleware(['permission:category-packages.create'], only: ['create', 'store']),
            new Middleware(['permission:category-packages.edit'], only: ['edit', 'update']),
            new Middleware(['permission:category-packages.delete'], only: ['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index()
      {
         // Ambil data kategori paket dengan filter pencarian
         $categories = CategoryPackage::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
         })->latest()->paginate(10);

         // Tambahkan parameter pencarian ke pagination
         $categories->appends(['search' => request()->search]);

         // Kirim ke view
         return view('account.category-packages.index', compact('categories'));
      }

      /**
       * create
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function create()
      {
         // Tampilkan form untuk membuat kategori paket baru
         return view('account.category-packages.create');
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
            'name'        => 'required|unique:category_packages,name',
            'description' => 'nullable',
         ]);

         // Simpan kategori paket baru
         CategoryPackage::create([
            'name'        => $request->name,
            'description' => $request->description,
         ]);

         // Redirect dengan pesan sukses
         return redirect()->route('account.category-packages.index')->with('success', 'Category created successfully');
      }

      /**
       * edit
       *
       * @param  mixed $id
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function edit($id)
      {
         // get category package by id
         $category_package = CategoryPackage::findOrFail($id);

         // Tampilkan form untuk mengedit kategori paket
         return view('account.category-packages.edit', compact('category_package'));
      }

      /**
       * update
       *
       * @param  mixed $request
       * @param  mixed $categoryPackage
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function update(Request $request, CategoryPackage $categoryPackage)
      {
         // Validasi input
         $request->validate([
            'name'        => 'required|unique:category_packages,name,' . $categoryPackage->id,
            'description' => 'nullable',
         ]);

         // Update kategori paket
         $categoryPackage->update([
            'name'        => $request->name,
            'description' => $request->description,
         ]);

         // Redirect dengan pesan sukses
         return redirect()->route('account.category-packages.index')->with('success', 'Category updated successfully');
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
         // Get category package by id
         $category = CategoryPackage::findOrFail($id);

         //delete category package
         $category->delete();

         // Redirect to index with success message
         return redirect()->route('account.category-packages.index')->with('success', 'Category deleted successfully');
      }
   }
