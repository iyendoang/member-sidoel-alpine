@extends('layouts.account')

@section('title', 'Outlets')

@section('content')
   <div class="mb-8">

      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Outlets Management</h2>
            <p class="text-gray-600 mt-1 italic">Manage your outlets and their information</p>
         </div>

         @can('outlets.create')
            <div class="mt-4 sm:mt-0">
               <a href="{{ route('account.outlets.create') }}"
                  class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors text-sm">
                  <x-icons.plus class="mr-2" width="20" height="20"/> Add New
               </a>
            </div>
         @endcan
      </div>

      <!-- Search -->
      <div class="mt-4 sm:w-1/3 relative">
         <form action="{{ route('account.outlets.index') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                   class="w-full text-sm bg-gray-100 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
               <x-icons.search class="text-gray-400" width="20" height="20"/>
            </div>
         </form>
      </div>

      <!-- Desktop Table -->
      <div class="hidden sm:block bg-white rounded-xl shadow overflow-hidden mt-5">
         <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
               <thead class="bg-gray-50 sticky top-0 z-10">
               <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Phone</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider max-w-xs">Notes</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
               </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
               @foreach($outlets as $outlet)
                  <tr class="hover:bg-gray-50 transition-colors">
                     <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                     <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $outlet->name }}</td>
                     <td class="px-6 py-4 text-sm text-gray-900">{{ $outlet->address }}</td>
                     <td class="px-6 py-4 text-sm text-gray-900">{{ $outlet->phone }}</td>
                     <td class="px-6 py-4 text-sm text-gray-900 max-w-xs break-words">
                        {!! $outlet->notes !!}
                     </td>
                     <td class="px-6 py-4 flex justify-center gap-3">
                        @can('outlets.edit')
                           <a href="{{ route('account.outlets.edit', $outlet->id) }}" class="text-primary hover:text-primary/90">
                              <x-icons.pencil width="20" height="20"/>
                           </a>
                        @endcan
                        @can('outlets.delete')
                           <button @click="$store.deleteModal.open('{{ route('account.outlets.destroy', $outlet->id) }}')"
                                   class="text-red-500 hover:text-red-500/90 cursor-pointer">
                              <x-icons.trash width="20" height="20"/>
                           </button>
                        @endcan
                     </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
      </div>

      <!-- Mobile Card View -->
      <div class="sm:hidden mt-5 space-y-4">
         @foreach($outlets as $outlet)
            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
               <div class="flex justify-between items-start">
                  <div>
                     <div class="text-sm font-medium text-gray-900">{{ $outlet->name }}</div>
                     <div class="text-xs text-gray-500 mt-1">{{ $outlet->address }}</div>
                  </div>
                  <div class="flex gap-2">
                     @can('outlets.edit')
                        <a href="{{ route('account.outlets.edit', $outlet->id) }}" class="text-primary hover:text-primary/90">
                           <x-icons.pencil width="20" height="20"/>
                        </a>
                     @endcan
                     @can('outlets.delete')
                        <button @click="$store.deleteModal.open('{{ route('account.outlets.destroy', $outlet->id) }}')"
                                class="text-red-500 hover:text-red-500/90 cursor-pointer">
                           <x-icons.trash width="20" height="20"/>
                        </button>
                     @endcan
                  </div>
               </div>
               <div class="mt-2 text-sm text-gray-700">
                  <div><strong>Phone:</strong> {{ $outlet->phone }}</div>
                  <div class="mt-1"><strong>Notes:</strong>
                     <div class="mt-1 line-clamp-4">
                        {!! $outlet->notes !!}
                     </div>
                  </div>
               </div>
            </div>
         @endforeach
      </div>

      <!-- Pagination -->
      <div class="mt-5">
         {{ $outlets->links('vendor.pagination.tailwind') }}
      </div>

      <!-- Delete Modal -->
      <x-modal-delete/>

   </div>
@endsection
