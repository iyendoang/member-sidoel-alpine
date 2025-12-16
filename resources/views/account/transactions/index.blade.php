@extends('layouts.account')

@section('title', 'Transactions')

@section('content')

   <!-- Header -->
   <div class="mb-8" x-data>

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Transactions Management</h2>
            <p class="text-gray-600 mt-1 italic">Manage your transactions data</p>
         </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <!-- Table Header -->
         <div class="px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
               @can('transactions.create')
                  <a href="{{ route('account.transactions.create') }}" class="inline-flex text-sm items-center px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                     <x-icons.plus class="mr-1" width="20" height="20" />
                     Add New
                  </a>
               @endcan
               <div class="block relative">
                  <form action="{{ route('account.transactions.index') }}">
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
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outlet</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
               </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
               @foreach($transactions as $transaction)
                  <tr>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $loop->iteration }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $transaction->outlet->name ?? '-' }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $transaction->invoice }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @php
                           $status = strtoupper($transaction->status);
                           $statusStyles = [
                               'NEW' => 'bg-blue-100 text-blue-800',
                               'IN PROGRESS' => 'bg-yellow-100 text-yellow-800',
                               'COMPLETED' => 'bg-green-100 text-green-800',
                               'CANCELLED' => 'bg-red-100 text-red-800',
                           ];
                        @endphp

                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucwords(strtolower($status)) }}
                            </span>
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $transaction->customer->name ?? '-' }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y H:i:s') }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @php
                           $paymentStatus = strtoupper($transaction->payment_status);
                           $paymentStyles = [
                               'UNPAID' => 'bg-red-100 text-red-800',
                               'PAID' => 'bg-green-100 text-green-800',
                           ];
                        @endphp

                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $paymentStyles[$paymentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(strtolower($paymentStatus)) }}
                            </span>
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($transaction->total_price, 2, ',', '.') }}
                     </td>
                     <td class="px-6 py-4 flex text-sm font-medium">
                        @can('transactions.print')
                           <div x-data="{ open: false }" class="relative inline-block text-left">
                              <!-- Dropdown button -->
                              <button @click="open = !open" type="button" class="text-gray-600 hover:text-gray-600/90 mr-4 flex items-center">
                                 <x-icons.printer width="20" height="20" />
                                 <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                 </svg>
                              </button>

                              <!-- Dropdown menu -->
                              <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black/5 z-50">
                                 <div class="py-1" role="menu" aria-orientation="vertical">
                                    <a href="{{ route('account.transactions.print', $transaction->id) }}?type=regular" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                       <div class="flex items-center">
                                          <x-icons.receipt width="16" height="16" class="mr-2" />
                                          Regular Print
                                       </div>
                                    </a>
                                    <a href="{{ route('account.transactions.print', $transaction->id) }}?type=thermal" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                       <div class="flex items-center">
                                          <x-icons.receipt width="16" height="16" class="mr-2" />
                                          Thermal Print
                                       </div>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        @endcan
                        @can('transactions.edit')
                           <a href="{{ route('account.transactions.edit', $transaction->id) }}" class="text-primary hover:text-primary/90 mr-4">
                              <x-icons.pencil width="20" height="20" />
                           </a>
                        @endcan
                        @can('transactions.delete')
                           <button
                                   @click="$store.deleteModal.open('{{ route('account.transactions.destroy', $transaction->id) }}')"
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
         {{ $transactions->links('vendor.pagination.tailwind') }}
      </div>

      <!-- Include the delete modal -->
      <x-modal-delete />

   </div>

@endsection
