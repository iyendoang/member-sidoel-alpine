<?php

   namespace App\Http\Controllers\Account;

   use App\Models\Outlet;
   use App\Models\Expense;
   use Illuminate\Http\Request;
   use App\Models\CategoryExpense;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class ExpenseController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array {
         return [
            new Middleware(['permission:expenses.index'], only:['index']),
            new Middleware(['permission:expenses.create'], only:['create', 'store']),
            new Middleware(['permission:expenses.edit'], only:['edit', 'update']),
            new Middleware(['permission:expenses.delete'], only:['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index() {
         // Ambil data expenses dengan filter pencarian
         $expenses = Expense::byOutlet()->with(['outlet', 'category_expense'])
                            ->when(request()->search, function($query) {
                               $query->where('description', 'like', '%' . request()->search . '%');
                            })
                            ->latest()
                            ->paginate(10);
         // Tambahkan parameter pencarian ke pagination
         $expenses->appends(['search' => request()->search]);

         // Kirim ke view
         return view('account.expenses.index', compact('expenses'));
      }

      /**
       * Show the form for creating a new resource.
       */
      public function create() {
         // get all outlets
         $outlets = Outlet::all();
         // get all categories
         $categories = CategoryExpense::all();

         // send to view
         return view('account.expenses.create', compact('outlets', 'categories'));
      }

      /**
       * Store a newly created resource in storage.
       */
      public function store(Request $request) {
         //validate request
         $request->validate([
            'category_expense_id' => 'required|exists:category_expenses,id',
            'amount'              => 'required|numeric',
            'description'         => 'nullable|string',
            'expense_date'        => 'required|date',
         ]);
         //check is admin
         if(auth()->user()->can('admin')){
            $outlet = $request->outlet_id;
         }
         else {
            $outlet = auth()->user()->outlet_id;
         }
         //create expense
         Expense::create([
            'outlet_id'           => $outlet,
            'category_expense_id' => $request->category_expense_id,
            'amount'              => $request->amount,
            'description'         => $request->description,
            'expense_date'        => $request->expense_date,
         ]);

         //redirect to index with success message
         return redirect()->route('account.expenses.index')->with('success', 'Expense created successfully');
      }

      /**
       * edit
       *
       * @param mixed $id
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function edit($id) {
         //find expense by id
         $expense = Expense::findOrFail($id);
         // get all outlets
         $outlets = Outlet::all();
         // get all categories
         $categories = CategoryExpense::all();

         // send to view
         return view('account.expenses.edit', compact('expense', 'outlets', 'categories'));
      }

      /**
       * update
       *
       * @param mixed $request
       * @param mixed $expense
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function update(Request $request, Expense $expense) {
         $request->validate([
            'category_expense_id' => 'required|exists:category_expenses,id',
            'amount'              => 'required|numeric',
            'description'         => 'nullable|string',
            'expense_date'        => 'required|date',
         ]);
         //check is admin
         if(auth()->user()->can('admin')){
            $outlet = $request->outlet_id;
         }
         else {
            $outlet = auth()->user()->outlet_id;
         }
         //update expense
         $expense->update([
            'outlet_id'           => $outlet,
            'category_expense_id' => $request->category_expense_id,
            'amount'              => $request->amount,
            'description'         => $request->description,
            'expense_date'        => $request->expense_date,
         ]);

         //redirect to index with success message
         return redirect()->route('account.expenses.index')->with('success', 'Expense updated successfully');
      }

      /**
       * destroy
       *
       * @param mixed $id
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function destroy($id) {
         // get expense by id
         $expense = Expense::findOrFail($id);
         // delete expense
         $expense->delete();

         // redirect to index with success message
         return redirect()->route('account.expenses.index')->with('success', 'Expense deleted successfully');
      }
   }
