@extends('layouts.account')

@section('title', 'Customers')

@section('content')

   <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Customers Management</h2>
            <p class="text-gray-600 mt-1 italic">Manage your customers and their information</p>
         </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">

         <div class="px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">

               <div class="flex items-center gap-2">
                  @can('customers.create')
                     <a href="{{ route('account.customers.create') }}"
                        class="inline-flex text-sm items-center px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                        <x-icons.plus class="mr-1" width="20" height="20"/>
                        Add New
                     </a>
                  @endcan

                  @can(['customers.import','customers.export'])
                     <a href="{{ route('account.customers.import-view') }}"
                        class="inline-flex text-sm items-center px-3 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-700/90">
                        <x-icons.arrows-sort class="mr-1" width="20" height="20"/>
                        Import / Export
                     </a>
                  @endcan
               </div>

               <div class="block relative">
                  <form action="{{ route('account.customers.index') }}">
                     <input type="text" placeholder="Search..." name="search" value="{{ request('search') }}"
                            class="w-full text-sm bg-gray-100 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icons.search class="text-gray-400" width="20" height="20"/>
                     </div>
                  </form>
               </div>
            </div>
         </div>

         <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
               {{-- ... isi tabel tidak berubah ... --}}
               <thead class="bg-gray-50">
               <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Office Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outlet</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
               </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
               @foreach($customers as $customer)
                  <tr>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer->name }}</td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->office_name }}</td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->phone }}</td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->address }}</td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->outlet->name ?? '-' }}</td>
                     <td class="px-6 py-4 flex text-sm font-medium">
                        @can('customers.edit')
                           <a href="{{ route('account.customers.edit', $customer->id) }}" class="text-primary hover:text-primary/90 mr-4"><x-icons.pencil width="20" height="20"/></a>
                        @endcan
                        @can('customers.delete')
                           <button @click="$store.deleteModal.open('{{ route('account.customers.destroy', $customer->id) }}')" class="text-red-500 hover:text-red-500/90 cursor-pointer"><x-icons.trash width="20" height="20"/></button>
                        @endcan
                     </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
      </div>

      <div class="mt-5">
         {{ $customers->links('vendor.pagination.tailwind') }}
      </div>

      <x-modal-delete/>

   </div>
@endsection