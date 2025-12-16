<?php

   namespace App\Http\Controllers\Account;

   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;
   use Spatie\Permission\Models\Permission;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class PermissionController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array {
         return [
            new Middleware(['permission:permissions.index'], only:['index']),
            new Middleware(['permission:permissions.create'], only:['create', 'store']),
            new Middleware(['permission:permissions.edit'], only:['edit', 'update']),
            new Middleware(['permission:permissions.delete'], only:['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index() {
         // Ambil data permission dengan filter pencarian
         $permissions = Permission::when(request()->search, function($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
         })->latest()->paginate(10);
         // Tambahkan parameter pencarian ke pagination
         $permissions->appends(['search' => request()->search]);

         // Kirim ke view
         return view('account.permissions.index', compact('permissions'));
      }

      /**
       * create
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function create() {
         return view('account.permissions.create');
      }

      /**
       * store
       *
       * @param mixed $request
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function store(Request $request) {
         // Validasi input
         $request->validate([
            'name' => 'required|unique:permissions,name',
         ]);
         // Buat permission baru
         Permission::create([
            'name'       => $request->name,
            'guard_name' => 'web',
         ]);

         // Redirect dengan pesan sukses
         return redirect()->route('account.permissions.index')->with('success', 'Permission created successfully.');
      }

      /**
       * edit
       *
       * @param mixed $id
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function edit($id) {
         //get permission
         $permission = Permission::findOrFail($id);

         //return view
         return view('account.permissions.edit', compact('permission'));
      }

      /**
       * Update the specified permission.
       */
      public function update(Request $request, Permission $permission) {
         // Validate the request
         $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
         ]);
         //update permission
         $permission->update([
            'name' => $request->name,
         ]);

         // Redirect with success message
         return redirect()->route('account.permissions.index')->with('success', 'Permission updated successfully.');
      }

      /**
       * destroy
       *
       * @param mixed $id
       *
       * @return \Illuminate\Http\RedirectResponse
       */
      public function destroy($id) {
         //get permission by id
         $permission = Permission::findOrFail($id);
         //delete permission
         $permission->delete();

         // Redirect with success message
         return redirect()->route('account.permissions.index')->with('success', 'Permission deleted successfully.');
      }
   }
