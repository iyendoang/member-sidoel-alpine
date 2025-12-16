<?php

   namespace App\Http\Controllers\Account;

   use App\Models\CategoryExpense;
   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\HasMiddleware;
   use Illuminate\Routing\Controllers\Middleware;

   class CategoryExpenseController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:category-expenses.index'], only: ['index']),
            new Middleware(['permission:category-expenses.create'], only: ['create', 'store']),
            new Middleware(['permission:category-expenses.edit'], only: ['edit', 'update']),
            new Middleware(['permission:category-expenses.delete'], only: ['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index()
      {
         // Ambil data kategori pengeluaran dengan filter pencarian
         $categories = CategoryExpense::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
         })->latest()->paginate(10);

         // Tambahkan parameter pencarian ke pagination
         $categories->appends(['search' => request()->search]);

         // Kirim ke view
         return view('account.category-expenses.index', compact('categories'));
      }

      /**
       * create
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function create()
      {
         // Tampilkan form untuk membuat kategori pengeluaran baru
         return view('account.category-expenses.create');
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
            'name'        => 'required|unique:category_expenses,name',
            'description' => 'nullable',
         ]);

         // Simpan kategori pengeluaran baru
         CategoryExpense::create([
            'name'        => $request->name,
            'description' => $request->description,
         ]);

         // Redirect dengan pesan sukses
         return redirect()->route('account.category-expenses.index')->with('success', 'Category created successfully');
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
         //get the category expense by id
         $category_expense = CategoryExpense::findOrFail($id);

         // kirim ke view untuk diedit
         return view('account.category-expenses.edit', compact('category_expense'));
      }

      /**
       * update
       *
       * @param  mixed $request
       * @param  mixed $categoryExpense
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function update(Request $request, CategoryExpense $categoryExpense)
      {
         // Validasi input
         $request->validate([
            'name'        => 'required|unique:category_expenses,name,' . $categoryExpense->id,
            'description' => 'nullable',
         ]);

         // Update kategori pengeluaran
         $categoryExpense->update([
            'name'        => $request->name,
            'description' => $request->description,
         ]);

         // Redirect dengan pesan sukses
         return redirect()->route('account.category-expenses.index')->with('success', 'Category updated successfully');
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
         //get the category expense by id
         $category = CategoryExpense::findOrFail($id);

         //delete the category
         $category->delete();

         //redirect with success message
         return redirect()->route('account.category-expenses.index')->with('success', 'Category deleted successfully');
      }
   }
