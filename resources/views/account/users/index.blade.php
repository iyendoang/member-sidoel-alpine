@extends('layouts.account')

@section('title', 'Users')

@section('content')

   <!-- Header -->
   <div class="mb-8" x-data>

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Users Management</h2>
            <p class="text-gray-600 mt-1 italic">Manage your users</p>
         </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <!-- Table Header -->
         <div class="px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
               @can('users.create')
                  <a href="{{ route('account.users.create') }}" class="inline-flex text-sm items-center px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                     <x-icons.plus class="mr-1" width="20" height="20" />
                     Add New
                  </a>
               @endcan
               <div class="block relative">
                  <form action="{{ route('account.users.index') }}">
                     <input type="text" placeholder="Search..." name="search" value="{{ request('search') }}" class="w-full text-sm bg-gray-100 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icons.search class="text-gray-400" width="20" height="20" />
                     </div>
                  </form>
               </div>
            </div>
         </div>

         <!-- Table Content -->
         <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
               <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outlet</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
               </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
               @foreach($users as $user)
                  <tr>
                     <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $loop->iteration }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $user->name }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $user->email }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @foreach($user->roles as $role)
                           <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">{{ $role->name }}</span>
                        @endforeach
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $user->outlet->name ?? '-' }}
                     </td>
                     <td class="px-6 py-4 flex text-center text-sm font-medium">
                        @can('users.edit')
                           <a href="{{ route('account.users.edit', $user->id) }}" class="text-primary hover:text-primary/90 mr-4">
                              <x-icons.pencil width="20" height="20" />
                           </a>
                        @endcan

                        @can('users.delete')
                           <button
                                   @click="$store.deleteModal.open('{{ route('account.users.destroy', $user->id) }}')"
                                   class="text-red-500 hover:text-red-500/90 cursor-pointer">
                              <x-icons.trash width="20" height="20" />
                           </button>
                        @endcan
                     </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
      </div>

      <div class="mt-5">
         {{ $users->links('vendor.pagination.tailwind') }}
      </div>

      <!-- Include the delete modal -->
      <x-modal-delete />

   </div>

@endsection
