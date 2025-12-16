@extends('layouts.account')

@section('title', 'Transactions Status')

@section('content')
   <div class="mb-8" x-data="transactionsInline()">

      <!-- Toast -->
      <div x-show="toast.show" x-transition
           x-text="toast.message"
           :class="toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
           class="fixed top-5 right-5 text-white px-4 py-2 rounded shadow-lg z-50"
           x-init="setTimeout(() => toast.show = false, 3000)">
      </div>

      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
         <div>
            <h2 class="text-xl font-bold text-gray-900">Transactions Status Management</h2>
            <p class="text-gray-600 mt-1 italic">Click "Edit" to modify Status, Domain, Level, District. Expired transactions are highlighted.</p>
         </div>
         <div class="block relative mt-3 sm:mt-0">
            <form action="{{ route('account.status-transactions.index') }}">
               <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice..."
                      class="w-full sm:w-64 text-sm bg-gray-100 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
               <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <x-icons.search class="text-gray-400" width="20" height="20"/>
               </div>
            </form>
         </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
         <table class="min-w-full divide-y divide-gray-200 text-xs">
            <thead class="bg-gray-50">
            <tr>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-8">No.</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-10">Outlet</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-44">Customer / Domain</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-32">Due Date</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-32">Status</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-32">Domain</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-32">Level</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-32">District</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-24">Payment</th>
               <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase w-16">Action</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($transactions as $transaction)
               @php
                  $isExpired = \Carbon\Carbon::parse($transaction->deadline)->isPast();
                  $host = parse_url($transaction->domain ?? '', PHP_URL_HOST) ?? $transaction->domain;
                  $displayDomain = \Illuminate\Support\Str::limit($host, 20);
                  $paymentStatus = strtoupper($transaction->payment_status);
                  $paymentStyles = [
                      'UNPAID' => 'bg-red-100 text-red-800',
                      'PAID' => 'bg-green-100 text-green-800',
                  ];
               @endphp
               <tr :class="{'bg-gray-50': editedId === {{ $transaction->id }}}">
                  <td class="px-3 py-2 whitespace-nowrap text-center">{{ $loop->iteration }}</td>
                  <td class="px-3 py-2 whitespace-nowrap truncate" title="{{ $transaction->outlet->name ?? '-' }}">
                     {{ $transaction->outlet->name ?? '-' }}
                  </td>
                  <td class="px-3 py-2 max-w-xs">
                     <div class="flex flex-col space-y-1">
                        <div class="font-medium text-gray-900 truncate" title="{{ $transaction->customer->name ?? '-' }}">
                           {{ $transaction->customer->name ?? '-' }}
                        </div>
                        @if($transaction->domain)
                           <div class="truncate" title="{{ $host }}">
                              <a href="{{ $transaction->domain }}" target="_blank" rel="noopener noreferrer"
                                 class="text-primary hover:underline">
                                 {{ $displayDomain }}
                              </a>
                           </div>
                        @endif
                     </div>
                  </td>
                  <td class="px-3 py-2 whitespace-nowrap">
                    <span class="{{ $isExpired ? 'text-red-600 font-bold' : '' }}">
                        {{ \Carbon\Carbon::parse($transaction->deadline)->format('d M Y H:i') }}
                       @if($isExpired) (Expired) @endif
                    </span>
                  </td>

                  <!-- Editable Fields -->
                  <td class="px-3 py-2">
                     <select x-model="transactions[{{ $transaction->id }}].status"
                             :disabled="editedId !== {{ $transaction->id }}"
                             class="border border-gray-300 rounded px-2 py-1 w-full text-xs">
                        <option value="NEW">NEW</option>
                        <option value="IN PROGRESS">IN PROGRESS</option>
                        <option value="COMPLETED">COMPLETED</option>
                        <option value="CANCELLED">CANCELLED</option>
                     </select>
                  </td>

                  <td class="px-3 py-2">
                     <input type="text" x-model="transactions[{{ $transaction->id }}].domain"
                            :disabled="editedId !== {{ $transaction->id }}"
                            class="border border-gray-300 rounded px-2 py-1 w-full text-xs truncate">
                  </td>

                  <td class="px-3 py-2">
                     <select x-model="transactions[{{ $transaction->id }}].level"
                             :disabled="editedId !== {{ $transaction->id }}"
                             class="border border-gray-300 rounded px-2 py-1 w-full text-xs">
                        <option value="">-- Select --</option>
                        <option value="RA">RA</option>
                        <option value="MI">MI</option>
                        <option value="MTS">MTS</option>
                        <option value="MA">MA</option>
                     </select>
                  </td>

                  <td class="px-3 py-2">
                     <input type="text" x-model="transactions[{{ $transaction->id }}].district"
                            :disabled="editedId !== {{ $transaction->id }}"
                            class="border border-gray-300 rounded px-2 py-1 w-full text-xs truncate">
                  </td>

                  <!-- Read-only -->
                  <td class="px-3 py-2 whitespace-nowrap text-center">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $paymentStyles[$paymentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(strtolower($paymentStatus)) }}
                    </span>
                  </td>

                  <!-- Action Button -->
                  <td class="px-3 py-2 text-center">
                     <button @click.prevent="toggleEdit({{ $transaction->id }})" class="cursor-pointer">
                        <template x-if="editedId !== {{ $transaction->id }}">
                           <x-icons.pencil class="text-red-500 hover:text-red-500/90" width="18" height="18"/>
                        </template>
                        <template x-if="editedId === {{ $transaction->id }}">
                           <x-icons.device-floppy class="text-primary hover:text-primary/90" width="18" height="18"/>
                        </template>
                     </button>
                  </td>
               </tr>
            @endforeach
            </tbody>
         </table>
      </div>

      <div class="mt-4">
         {{ $transactions->links('vendor.pagination.tailwind') }}
      </div>
   </div>

   <script>
       function transactionsInline() {
           return {
               editedId: null,
               transactions: {
                  @foreach($transactions as $t)
                          {{ $t->id }}: {
                      status: '{{ $t->status }}',
                      domain: '{{ $t->domain ?? '' }}',
                      level: '{{ $t->level ?? '' }}',
                      district: '{{ $t->district ?? '' }}',
                  },
                  @endforeach
               },
               toast: {
                   show: false,
                   message: '',
                   type: 'success' // success | error
               },
               toggleEdit(id) {
                   if (this.editedId === id) {
                       this.updateField(id);
                   } else {
                       this.editedId = id;
                   }
               },
               updateField(id) {
                   fetch(`/account/status-transactions/${id}`, {
                       method: 'PUT',
                       headers: {
                           'Content-Type': 'application/json',
                           'X-CSRF-TOKEN': '{{ csrf_token() }}',
                           'Accept': 'application/json'
                       },
                       body: JSON.stringify(this.transactions[id])
                   })
                       .then(async res => {
                           if (!res.ok) {
                               const err = await res.json();
                               throw err;
                           }
                           return res.json();
                       })
                       .then(data => {
                           this.transactions[id] = { ...this.transactions[id], ...data.data };
                           this.editedId = null;
                           this.showToast('Transaction updated successfully', 'success');
                       })
                       .catch(err => {
                           this.editedId = null;
                           let message = 'Update failed';
                           if (err.errors) {
                               message = Object.values(err.errors).flat().join("\n");
                           } else if (err.message) {
                               message = err.message;
                           }
                           this.showToast(message, 'error');
                       });
               },
               showToast(message, type) {
                   this.toast.message = message;
                   this.toast.type = type;
                   this.toast.show = true;
                   setTimeout(() => this.toast.show = false, 3000);
               }
           }
       }
   </script>
@endsection
