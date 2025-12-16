<?php

   namespace App\Http\Controllers\Account;

   use Illuminate\Http\Request;
   use Spatie\Permission\Models\Role;
   use App\Http\Controllers\Controller;
   use Spatie\Permission\Models\Permission;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class RoleController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:roles.index'], only: ['index']),
            new Middleware(['permission:roles.create'], only: ['create', 'store']),
            new Middleware(['permission:roles.edit'], only: ['edit', 'update']),
            new Middleware(['permission:roles.delete'], only: ['destroy']),
         ];
      }

      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index()
      {
         // Ambil data roles dengan filter pencarian
         $roles = Role::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
         })->latest()->paginate(10);

         // Tambahkan parameter pencarian ke pagination
         $roles->appends(['search' => request()->search]);

         // Kirim ke view
         return view('account.roles.index', compact('roles'));
      }

      /**
       * create
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function create()
      {
         // Ambil semua permission dan urutkan berdasarkan name
         $data = Permission::orderBy('name')->get();

         // Group berdasarkan prefix sebelum titik pertama
         $permissions = $data->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
         });

         //return view
         return view('account.roles.create', compact('permissions'));
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
            'name'          => 'required|unique:roles,name',
            'permissions'   => 'array|nullable',
         ]);

         // insert role
         $role = Role::create([
            'name'          => $request->name,
            'guard_name'    => 'web',
         ]);

         //sync permissions
         $role->givePermissionTo($request->permissions);

         // Redirect dengan pesan sukses
         return redirect()->route('account.roles.index')->with('success', 'Role created successfully.');
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
         //get role
         $role = Role::findOrFail($id);

         // Ambil semua permission, lalu kelompokkan berdasarkan prefix sebelum titik
         $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
         });

         // Ambil permission yang dimiliki role (berdasarkan name)
         $rolePermissions = $role->permissions->pluck('name')->toArray();

         //return view
         return view('account.roles.edit', compact('role', 'permissions', 'rolePermissions'));
      }

      /**
       * Update the role.
       */
      public function update(Request $request, $id)
      {
         //get role
         $role = Role::findOrFail($id);

         $request->validate([
            'name'          => 'required|unique:roles,name,' . $id,
            'permissions'   => 'array|nullable',
         ]);

         //update
         $role->update(['name' => $request->name]);

         //sync
         $role->syncPermissions($request->permissions);

         //redirect
         return redirect()->route('account.roles.index')->with('success', 'Role updated successfully.');
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
         //get role
         $role = Role::findOrFail($id);

         //delete
         $role->delete();

         //redirect
         return redirect()->route('account.roles.index')->with('success', 'Role deleted successfully.');
      }
   }
