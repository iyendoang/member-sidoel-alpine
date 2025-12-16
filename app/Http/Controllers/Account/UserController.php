<?php

   namespace App\Http\Controllers\Account;

   use App\Models\User;
   use App\Models\Outlet;
   use Illuminate\Http\Request;
   use Spatie\Permission\Models\Role;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class UserController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:users.index'], only: ['index']),
            new Middleware(['permission:users.create'], only: ['create', 'store']),
            new Middleware(['permission:users.edit'], only: ['edit', 'update']),
            new Middleware(['permission:users.delete'], only: ['destroy']),
         ];
      }

      /**
       * index
       *
       * @return void
       */
      public function index()
      {
         // Ambil data users dengan filter pencarian
         $users = User::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%')
                  ->orWhere('email', 'like', '%' . request()->search . '%');
         })->latest()->paginate(10);

         // Tambahkan parameter pencarian ke pagination
         $users->appends(['search' => request()->search]);

         // Kirim ke view
         return view('account.users.index', compact('users'));
      }

      /**
       * create
       *
       * @return void
       */
      public function create()
      {
         // get all roles
         $roles = Role::orderBy('name')->get();

         //get all outlets
         $outlets = Outlet::orderBy('name')->get();

         // pass roles to the view
         return view('account.users.create', compact('roles', 'outlets'));
      }

      /**
       * store
       *
       * @param  mixed $request
       * @return void
       */
      public function store(Request $request)
      {
         // validate request
         $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'roles'     => 'array|nullable',
         ]);

         // create user
         $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'outlet_id' => $request->outlet_id
         ]);

         // assign roles
         $user->syncRoles($request->roles);

         // redirect with success message
         return redirect()->route('account.users.index')->with('success', 'User created successfully.');
      }

      /**
       * edit
       *
       * @param  mixed $id
       * @return void
       */
      public function edit($id)
      {
         // get user
         $user = User::findOrFail($id);

         // get all outlets
         $outlets = Outlet::orderBy('name')->get();

         // get all roles
         $roles = Role::orderBy('name')->get();

         // get user roles
         $userRoles = $user->roles->pluck('name')->toArray();

         return view('account.users.edit', compact('user', 'roles', 'outlets', 'userRoles'));
      }

      /**
       * Update the user.
       */
      public function update(Request $request, User $user)
      {
         // validate request
         $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6|confirmed',
            'roles'     => 'array|nullable',
         ]);

         // update
         $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'outlet_id' => $request->outlet_id
         ];

         // check if password is filled
         if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
         }

         // update
         $user->update($data);

         // sync roles
         $user->syncRoles($request->roles);

         // redirect with success message
         return redirect()->route('account.users.index')->with('success', 'User updated successfully.');
      }

      /**
       * destroy
       *
       * @param  mixed $id
       * @return void
       */
      public function destroy($id)
      {
         // get user by id
         $user = User::findOrFail($id);

         // delete user
         $user->delete();

         // redirect with success message
         return redirect()->route('account.users.index')->with('success', 'User deleted successfully.');
      }
   }
