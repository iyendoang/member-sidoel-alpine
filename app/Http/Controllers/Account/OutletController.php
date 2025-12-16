<?php

   namespace App\Http\Controllers\Account;

   use App\Models\Outlet;
   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;
   use HTMLPurifier;
   use HTMLPurifier_Config;

   class OutletController extends Controller implements HasMiddleware
   {
      /**
       * Get the middleware that should be assigned to the controller.
       *
       * @return array
       */
      public static function middleware(): array {
         return [
            new Middleware(['permission:outlets.index'], only:['index']),
            new Middleware(['permission:outlets.create'], only:['create', 'store']),
            new Middleware(['permission:outlets.edit'], only:['edit', 'update']),
            new Middleware(['permission:outlets.delete'], only:['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index() {
         //get the outlets with search functionality
         $outlets = Outlet::when(request()->search, function($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
         })->latest()->paginate(10);
         //set the search query parameter for pagination
         $outlets->appends(['search' => request()->search]);

         //return the view with the outlets
         return view('account.outlets.index', compact('outlets'));
      }

      /**
       * create
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function create() {
         return view('account.outlets.create');
      }

      /**
       * store
       *
       * @param mixed $request
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function store(Request $request) {
         $request->validate([
            'code_outlet' => 'required|unique:outlets',
            'name'        => 'required',
            'address'     => 'required',
            'phone'       => 'required',
            'notes'       => 'nullable|string',
         ]);
         // Sanitasi notes
         $config = HTMLPurifier_Config::createDefault();
         $purifier = new HTMLPurifier($config);
         $notes = $purifier->purify($request->notes);
         Outlet::create([
            'code_outlet' => $request->code_outlet,
            'name'        => $request->name,
            'address'     => $request->address,
            'phone'       => $request->phone,
            'notes'       => $notes,
         ]);

         return redirect()->route('account.outlets.index')->with('success', 'Outlet created successfully');
      }

      /**
       * edit
       *
       * @param mixed $id
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function edit($id) {
         //get outlet
         $outlet = Outlet::findOrFail($id);

         //return view
         return view('account.outlets.edit', compact('outlet'));
      }

      /**
       * update
       *
       * @param mixed $request
       * @param mixed $outlet
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function update(Request $request, Outlet $outlet) {
         $request->validate([
            'code_outlet' => 'required|unique:outlets,code_outlet,' . $outlet->id,
            'name'        => 'required',
            'address'     => 'required',
            'phone'       => 'required',
            'notes'       => 'nullable|string',
         ]);
         // Sanitasi notes
         $config = HTMLPurifier_Config::createDefault();
         $purifier = new HTMLPurifier($config);
         $notes = $purifier->purify($request->notes);
         $outlet->update([
            'code_outlet' => $request->code_outlet,
            'name'        => $request->name,
            'address'     => $request->address,
            'phone'       => $request->phone,
            'notes'       => $notes,
         ]);

         return redirect()->route('account.outlets.index')->with('success', 'Outlet updated successfully');
      }

      /**
       * destroy
       *
       * @param mixed $id
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function destroy($id) {
         //delete
         $outlet = Outlet::findOrFail($id);
         //delete
         $outlet->delete();

         //redirect
         return redirect()->route('account.outlets.index')->with('success', 'Outlet deleted successfully');
      }
   }
