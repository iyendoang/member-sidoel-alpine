@extends('layouts.account')

@section('title', 'Create User')

@section('content')

   <div class="mb-8">

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Create User</h2>
            <p class="text-gray-600 mt-1 italic">Add a new user to the system</p>
         </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.users.store') }}">
               @csrf

               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Name -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Name</label>
                     <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" class="mt-1 block w-full border border-gray-300 @error('name') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('name')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <!-- Email -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Email</label>
                     <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="mt-1 block w-full border border-gray-300 @error('email') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('email')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <!-- Password -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Password</label>
                     <input type="password" name="password" placeholder="Password" class="mt-1 block w-full border border-gray-300 @error('password') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('password')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <!-- Confirm Password -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                     <input type="password" name="password_confirmation" placeholder="Confirm Password" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                  </div>
               </div>

               <!-- Outlet (Full Width) -->
               <div class="mt-4">
                  <label class="block text-sm font-medium text-gray-700">Outlet</label>
                  <select name="outlet_id" class="mt-1 block w-full border border-gray-300 @error('outlet_id') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     <option value="">-- Select Outlet --</option>
                     @foreach ($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>
                           {{ $outlet->name }}
                        </option>
                     @endforeach
                  </select>
                  @error('outlet_id')
                  <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                  @enderror
               </div>

               <!-- Roles -->
               <div class="mt-6">
                  <h2 class="text-md font-semibold text-gray-800 mb-2">Roles</h2>
                  <div class="grid grid-cols-1 gap-2">
                     @foreach ($roles as $role)
                        <div class="flex items-center space-x-2">
                           <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200">
                           <label for="role-{{ $role->id }}" class="text-gray-700">
                              {{ $role->name }}
                           </label>
                        </div>
                     @endforeach
                  </div>
                  @error('roles')
                  <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                  @enderror
               </div>

               <!-- Submit -->
               <div class="mt-6 flex justify-start">
                  <a href="{{ route('account.users.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                  <button type="submit" class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Save</button>
               </div>
            </form>

         </div>
      </div>

   </div>

@endsection

