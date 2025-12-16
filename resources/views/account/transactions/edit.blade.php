@extends('layouts.account')

@section('title', 'Edit Transaction')

@section('content')

   <div class="mb-8" x-data="transactionForm()" x-init="initEditForm()">

      <!-- Header Section -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Edit Transaction</h2>
            <p class="text-gray-600 mt-1 italic">Update transaction <strong>#{{ $transaction->invoice }}</strong></p>
         </div>
      </div>

      <!-- Main Form Container -->
      <div class="bg-white rounded-xl shadow overflow-hidden mt-5">
         <div class="p-6 max-w-7xl mx-auto">
            <div class="space-y-6">

               <!-- Customer and Date Information -->
               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                     <label class="block font-medium mb-1">Customer</label>
                     <select class="w-full border border-gray-200 rounded-xl px-3 py-2" x-model="customer_id" required>
                        <option value="">~ Select Customer ~</option>
                        @foreach ($customers as $customer)
                           <option value="{{ $customer->id }}" {{ $transaction->customer_id == $customer->id ? 'selected' : '' }}>
                              {{ $customer->name  . ' (' . $customer->office_name.')'}}
                           </option>
                        @endforeach
                     </select>
                  </div>
                  <!-- TRANSACTION DATE -->
                  <div>
                     <label class="block font-medium mb-1">Date</label>
                     <input
                             type="text"
                             x-ref="transactionDate"
                             x-model="transaction_date"
                             class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-white cursor-pointer"
                             readonly
                             required
                     >
                  </div>
                  <!-- DUE DATE -->
                  <div>
                     <label class="block font-medium mb-1">Due Date</label>
                     <input
                             type="text"
                             x-ref="dueDate"
                             x-model="due_date"
                             class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-white cursor-pointer"
                             readonly
                             required
                     >
                  </div>
               </div>

               <hr class="border-2 border-gray-200">

               <!-- Packages Section -->
               <div class="mt-6">
                  <h2 class="font-semibold mb-4 text-lg">Packages</h2>

                  <!-- Desktop Table -->
                  <div class="hidden sm:block overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                     <table class="w-full text-sm table-auto border-collapse">
                        <thead class="bg-gray-100">
                        <tr>
                           <th class="px-3 py-2 border-b border-gray-200 text-center">No</th>
                           <th class="px-3 py-2 border-b border-gray-200 text-left">Package</th>
                           <th class="px-3 py-2 border-b border-gray-200 text-left">Package Type</th>
                           <th class="px-3 py-2 border-b border-gray-200 text-center">Qty</th>
                           <th class="px-3 py-2 border-b border-gray-200 text-left">Unit</th>
                           <th class="px-3 py-2 border-b border-gray-200 text-right">Price</th>
                           <th class="px-3 py-2 border-b border-gray-200 text-right">Total</th>
                           <th class="px-3 py-2 border-b border-gray-200 text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(package, index) in packages" :key="index">
                           <tr class="hover:bg-gray-50">
                              <td class="px-3 py-2 text-center" x-text="index + 1"></td>
                              <td class="px-3 py-2">
                                 <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                    <input type="text" @click="openPackageSearch(index)"
                                           class="w-full px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-primary"
                                           x-model="package.name" readonly placeholder="Search Package">
                                    <button type="button" @click="openPackageSearch(index)"
                                            class="px-2 py-1 bg-gray-100 border-l border-gray-200 hover:bg-gray-200">
                                       <x-icons.search width="18" height="18"/>
                                    </button>
                                 </div>
                              </td>
                              <td class="px-3 py-2">
                                 <input type="text" readonly
                                        class="w-full bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-sm"
                                        x-model="package.type">
                              </td>
                              <td class="px-3 py-2 text-center">
                                 <input type="number" min="1"
                                        class="w-full border border-gray-200 rounded-lg px-2 py-1 text-sm text-center"
                                        x-model.number="package.qty" @input="updatePackageTotal(index)">
                              </td>
                              <td class="px-3 py-2">
                                 <select class="w-full border border-gray-200 rounded-lg px-2 py-1 text-sm"
                                         x-model="package.unit">
                                    <option value="">Unit</option>
                                    @foreach ($units as $unit)
                                       <option value="{{ $unit->name }}">{{ strtoupper($unit->name) }}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td class="px-3 py-2 text-right" x-text="formatCurrency(package.price)"></td>
                              <td class="px-3 py-2 text-right"
                                  x-text="formatCurrency(package.qty * package.price)"></td>
                              <td class="px-3 py-2 text-center">
                                 <button type="button" @click="removePackage(index)"
                                         class="text-red-500 hover:text-red-700 font-bold">✕
                                 </button>
                              </td>
                           </tr>
                        </template>
                        </tbody>
                     </table>
                  </div>

                  <!-- Mobile Card View -->
                  <div class="sm:hidden space-y-3">
                     <template x-for="(package, index) in packages" :key="index">
                        <div class="border border-gray-200 rounded-xl p-4 shadow-sm space-y-2">
                           <!-- Package Label & Remove Button -->
                           <div class="flex justify-between items-start">
                              <div class="w-full">
                                 <label class="block text-xs text-gray-500">Package</label>
                                 <div @click="openPackageSearch(index)"
                                      class="font-medium text-gray-800 border border-gray-200 rounded-lg px-2 py-1 cursor-pointer hover:bg-gray-50">
                                    <span x-text="package.name || 'Select Package'"></span>
                                 </div>
                              </div>
                              <button type="button" @click="removePackage(index)"
                                      class="text-red-500 hover:text-red-700 font-bold ml-2">✕
                              </button>
                           </div>

                           <!-- Package Type -->
                           <div>
                              <label class="block text-xs text-gray-500">Type</label>
                              <div class="text-sm text-gray-700" x-text="package.type"></div>
                           </div>

                           <!-- Qty & Unit -->
                           <div class="flex gap-2 items-center">
                              <div class="flex-1">
                                 <label class="block text-xs text-gray-500">Qty</label>
                                 <input type="number" min="1"
                                        class="w-full border border-gray-200 rounded-lg px-2 py-1 text-sm text-center"
                                        x-model.number="package.qty" @input="updatePackageTotal(index)">
                              </div>
                              <div class="flex-1">
                                 <label class="block text-xs text-gray-500">Unit</label>
                                 <select class="w-full border border-gray-200 rounded-lg px-2 py-1 text-sm"
                                         x-model="package.unit">
                                    <option value="">Unit</option>
                                    @foreach ($units as $unit)
                                       <option value="{{ $unit->name }}">{{ strtoupper($unit->name) }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>

                           <!-- Price & Total -->
                           <div class="flex justify-between text-sm text-gray-700">
                              <div>Price: <span x-text="formatCurrency(package.price)"></span></div>
                              <div>Total: <span x-text="formatCurrency(package.qty * package.price)"
                                                class="font-medium text-gray-900"></span></div>
                           </div>
                        </div>
                     </template>
                  </div>

                  <!-- Add Package Button -->
                  <button type="button" @click="addEmptyPackage()"
                          class="mt-2 bg-yellow-600 text-white px-3 py-1 rounded-xl text-sm">+ Add Package
                  </button>

                  <!-- Package Search Modal -->
                  <div x-show="showPackageModal" x-cloak
                       class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" x-transition>
                     <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl rounded-xl p-6"
                          @click.outside="showPackageModal = false">
                        <input type="text" x-model="packageSearchQuery" placeholder="Search package..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4 focus:ring-1 focus:ring-primary focus:outline-none"/>
                        <template x-for="(package, i) in filteredPackages" :key="i">
                           <div @click="selectPackage(package)"
                                class="cursor-pointer border-b border-gray-200 p-2 hover:bg-gray-100 rounded-lg mb-1">
                              <div class="font-medium text-gray-800" x-text="package.name"></div>
                              <div class="text-sm text-gray-500"
                                   x-text="'Type: ' + package.type + ', Price: ' + package.price.toFixed(2)"></div>
                           </div>
                        </template>
                        <div x-show="packageSearchQuery && filteredPackages.length === 0"
                             class="text-gray-500 text-sm text-center py-4">
                           No package found.
                        </div>
                     </div>
                  </div>
               </div>


               <!-- Summary Section -->
               <div class="grid md:grid-cols-2 gap-4">
                  <div></div>
                  <div class="space-y-2">
                     <div class="flex justify-between">
                        <span>Grand Total</span>
                        <span x-text="formatCurrency(calculateSummary().grandTotal)"></span>
                     </div>
                     <div class="flex justify-between items-center">
                        <span>Discount</span>
                        <div class="flex gap-1 items-center">
                           <input type="number" min="0" max="100" x-model.number="discountPercent"
                                  class="w-20 border border-gray-200 rounded-xl px-2 py-1 text-right"/>
                           <span>%</span>
                           <input type="text" :value="formatCurrency(calculateSummary().discountAmount)"
                                  class="w-24 cursor-not-allowed border border-gray-200 rounded-xl px-2 py-1 text-right bg-gray-100"
                                  readonly/>
                        </div>
                     </div>
                     <div class="flex justify-between items-center">
                        <span>Tax</span>
                        <div class="flex gap-1 items-center">
                           <input type="number" min="0" max="100" x-model.number="taxPercent"
                                  class="w-20 border border-gray-200 rounded-xl px-2 py-1 text-right"/>
                           <span>%</span>
                           <input type="text" :value="formatCurrency(calculateSummary().taxAmount)"
                                  class="w-24 cursor-not-allowed border border-gray-200 rounded-xl px-2 py-1 text-right bg-gray-100"
                                  readonly/>
                        </div>
                     </div>
                     <div class="flex justify-between items-center">
                        <span>Additional Fee</span>
                        <input type="number" min="0" x-model.number="additionalFee"
                               class="w-24 border border-gray-200 rounded-xl px-2 py-1 text-right"/>
                     </div>
                     <div class="flex justify-between font-semibold">
                        <span>Total Payment</span>
                        <span x-text="formatCurrency(calculateSummary().totalPayment)"></span>
                     </div>
                  </div>
               </div>

               <hr class="border-2 border-gray-200">

               <!-- Status Section -->
               <div class="grid md:grid-cols-3 gap-4">
                  <div>
                     <label class="block font-medium mb-1">Status</label>
                     <select class="w-full border rounded-xl border-gray-200 px-3 py-2" x-model="status">
                        <option value="">~ Select Status ~</option>
                        <option value="NEW">NEW</option>
                        <option value="IN PROGRESS">IN PROGRESS</option>
                        <option value="COMPLETED">COMPLETED</option>
                        <option value="CANCELLED">CANCELLED</option>
                     </select>
                  </div>
                  <div>
                     <label class="block font-medium mb-1">Payment Status</label>
                     <select class="w-full border border-gray-200 rounded-xl px-3 py-2" x-model="payment_status">
                        <option value="">~ Select Payment Status ~</option>
                        <option value="PAID">PAID</option>
                        <option value="UNPAID">UNPAID</option>
                     </select>
                  </div>
                  <div>
                     <label class="block font-medium mb-1">Payment Date</label>
                     <input
                             type="text"
                             x-ref="paymentDate"
                             x-model="payment_date"
                             :disabled="payment_status !== 'PAID'"
                             :class="payment_status !== 'PAID'
                                ? 'bg-gray-100 cursor-not-allowed'
                                : 'bg-white cursor-pointer'"
                             class="w-full border border-gray-200 rounded-xl px-3 py-2"
                             readonly
                     >
                  </div>
               </div>
               <!-- Notes Section -->
               <div class="mt-6">
                  <label class="block font-medium mb-1">Notes</label>

                  <!-- Hidden input (optional, untuk debugging) -->
                  <input type="hidden" x-model="notes">

                  <!-- Quill Editor -->
                  <div
                          x-ref="quillEditor"
                          class="bg-white border border-gray-200 rounded-xl"
                          style="min-height: 150px;"
                  ></div>
               </div>
               <!-- Action Buttons -->
               <div class="flex justify-between mt-6">
                  <div class="space-x-2">
                     <a href="{{ route('account.transactions.index') }}"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                     <button
                             @click="validateAndUpdate()"
                             :disabled="isSubmitting"
                             :class="isSubmitting
                                ? 'bg-blue-400 cursor-not-allowed'
                                : 'bg-blue-600 hover:bg-blue-700'"
                             class="text-white px-4 py-2 rounded-xl transition"
                     >
                        <span x-show="!isSubmitting">Update Transaction</span>
                        <span x-show="isSubmitting">Updating...</span>
                     </button>

                  </div>
               </div>

            </div>
         </div>
      </div>

   </div>

   <script>
       function transactionForm() {
           return {
               // ======================
               // FORM DATA
               // ======================
               packages: [],
               customer_id: null,
               transaction_date: '',
               due_date: '',
               payment_status: '',
               payment_date: '',
               status: '',
               transaction_id: {{ $transaction->id }},
               isSubmitting: false,
               notes: `{!! addslashes($transaction->notes ?? '') !!}`,


               // ======================
               // UI STATE
               // ======================
               showPackageModal: false,
               selectedPackageIndex: null,
               packageSearchQuery: '',

               // ======================
               // PRICING
               // ======================
               discountPercent: {{ $transaction->discount_percent ?? 0 }},
               taxPercent: {{ $transaction->tax_percent ?? 0 }},
               additionalFee: {{ $transaction->additional_fee ?? 0 }},

               // ======================
               // DATA SOURCE
               // ======================
               packageList: @json($packages),

               // ======================
               // FLATPICKR INSTANCE
               // ======================
               transactionPicker: null,
               duePicker: null,
               paymentPicker: null,

               // ======================
               // COMPUTED
               // ======================
               get filteredPackages() {
                   if (!this.packageSearchQuery) {
                       return this.packageList.slice(0, 5);
                   }
                   return this.packageList.filter(pkg =>
                       pkg.name.toLowerCase().includes(this.packageSearchQuery.toLowerCase()) ||
                       pkg.type.toLowerCase().includes(this.packageSearchQuery.toLowerCase())
                   );
               },

               // ======================
               // INIT
               // ======================
               initQuill() {
                   const quill = new Quill(this.$refs.quillEditor, {
                       theme: 'snow',
                       placeholder: 'Write notes here...',
                       modules: {
                           toolbar: [
                               ['bold', 'italic', 'underline'],
                               [{list: 'ordered'}, {list: 'bullet'}],
                               ['link'],
                               ['clean']
                           ]
                       }
                   });

                   // 👉 LOAD NOTES LAMA
                   if (this.notes) {
                       quill.root.innerHTML = this.notes;
                   }

                   // 👉 SYNC KE ALPINE
                   quill.on('text-change', () => {
                       this.notes = quill.root.innerHTML;
                   });
               },

               initEditForm() {
                   this.customer_id = {{ $transaction->customer_id }};
                   this.transaction_date = '{{ \Carbon\Carbon::parse($transaction->date)->format("Y-m-d H:i") }}';
                   this.due_date = '{{ \Carbon\Carbon::parse($transaction->deadline)->format("Y-m-d H:i") }}';
                   this.payment_status = '{{ $transaction->payment_status }}';
                   this.payment_date = '{{ $transaction->payment_date ? \Carbon\Carbon::parse($transaction->payment_date)->format("Y-m-d H:i") : "" }}';
                   this.status = '{{ $transaction->status }}';

                   // LOAD PACKAGES
                   this.packages = JSON.parse('{!! json_encode(
                $transaction->transaction_details->map(function($detail) {
                    return [
                        "id"    => $detail->package->id,
                        "name"  => $detail->package->name,
                        "type"  => $detail->package->category_package->name ?? "-",
                        "qty"   => $detail->quantity,
                        "unit"  => $detail->unit,
                        "price" => $detail->total / max($detail->quantity, 1),
                    ];
                })
            ) !!}');

                   this.initFlatpickr();
                   this.initQuill();

                   this.$watch('payment_status', value => {
                       if (value !== 'PAID') {
                           this.payment_date = '';
                           if (this.paymentPicker) this.paymentPicker.clear();
                       }
                   });
               },

               // ======================
               // FLATPICKR
               // ======================
               initFlatpickr() {
                   const self = this;

                   // TRANSACTION DATE
                   this.transactionPicker = flatpickr(this.$refs.transactionDate, {
                       enableTime: true,
                       time_24hr: true,
                       dateFormat: "Y-m-d H:i",
                       defaultDate: this.transaction_date || new Date(),

                       onChange(selectedDates) {
                           if (!selectedDates.length) return;

                           const date = selectedDates[0];
                           self.transaction_date = flatpickr.formatDate(date, "Y-m-d H:i");

                           // set min due_date
                           self.duePicker.set('minDate', date);

                           if (!self.due_date || new Date(self.due_date) < date) {
                               self.duePicker.setDate(date, true);
                               self.due_date = flatpickr.formatDate(date, "Y-m-d H:i");
                           }
                       }
                   });

                   // DUE DATE
                   this.duePicker = flatpickr(this.$refs.dueDate, {
                       enableTime: true,
                       time_24hr: true,
                       dateFormat: "Y-m-d H:i",
                       defaultDate: this.due_date || this.transaction_date,
                       minDate: this.transaction_date,

                       onChange(selectedDates) {
                           if (!selectedDates.length) return;

                           const date = selectedDates[0];
                           if (self.transaction_date &&
                               date < new Date(self.transaction_date)) {

                               Swal.fire({
                                   icon: 'warning',
                                   title: 'Opps!',
                                   text: 'Due date cannot be earlier than transaction date',
                                   showConfirmButton: false,
                                   timer: 1500
                               });

                               self.duePicker.setDate(self.transaction_date, true);
                               return;
                           }

                           self.due_date = flatpickr.formatDate(date, "Y-m-d H:i");
                       }
                   });

                   // PAYMENT DATE
                   this.paymentPicker = flatpickr(this.$refs.paymentDate, {
                       enableTime: true,
                       time_24hr: true,
                       dateFormat: "Y-m-d H:i",
                       defaultDate: this.payment_date || null,

                       onChange(selectedDates) {
                           if (!selectedDates.length) return;
                           self.payment_date = flatpickr.formatDate(selectedDates[0], "Y-m-d H:i");
                       }
                   });
               },

               // ======================
               // PACKAGE ACTIONS
               // ======================
               addEmptyPackage() {
                   this.packages.push({
                       id: '',
                       name: '',
                       type: '',
                       qty: 1,
                       unit: '',
                       price: 0
                   });
               },

               removePackage(index) {
                   if (this.packages.length > 1) {
                       this.packages.splice(index, 1);
                   } else {
                       this.toast('You must have at least one package');
                   }
               },

               updatePackageTotal(index) {
                   if (this.packages[index].qty < 1) {
                       this.packages[index].qty = 1;
                   }
               },

               openPackageSearch(index) {
                   this.selectedPackageIndex = index;
                   this.packageSearchQuery = '';
                   this.showPackageModal = true;
               },

               selectPackage(pkg) {
                   if (this.selectedPackageIndex === null) return;

                   this.packages[this.selectedPackageIndex] = {
                       id: pkg.id,
                       name: pkg.name,
                       type: pkg.type,
                       qty: 1,
                       unit: '',
                       price: pkg.price
                   };

                   this.showPackageModal = false;
                   this.selectedPackageIndex = null;
               },

               // ======================
               // SUMMARY
               // ======================
               calculateSummary() {
                   const grandTotal = this.packages.reduce(
                       (sum, p) => sum + (p.qty * p.price), 0
                   );

                   const discountAmount = (this.discountPercent / 100) * grandTotal;
                   const afterDiscount = grandTotal - discountAmount;
                   const taxAmount = (this.taxPercent / 100) * afterDiscount;

                   return {
                       grandTotal,
                       discountAmount,
                       taxAmount,
                       totalPayment: afterDiscount + taxAmount + this.additionalFee
                   };
               },

               formatCurrency(value) {
                   return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
               },

               // ======================
               // VALIDATION
               // ======================
               validateForm() {
                   if (!this.customer_id) return this.toast('Please select a customer');
                   if (!this.transaction_date) return this.toast('Please enter transaction date');
                   if (!this.due_date) return this.toast('Please enter due date');
                   if (!this.status) return this.toast('Please select transaction status');
                   if (!this.payment_status) return this.toast('Please select payment status');

                   if (this.payment_status === 'PAID' && !this.payment_date) {
                       return this.toast('Please enter payment date');
                   }

                   const invalidPackage = this.packages.some(p =>
                       !p.id || !p.unit || p.qty < 1
                   );

                   if (invalidPackage) {
                       return this.toast('Please complete package & unit');
                   }

                   return true;
               },

               toast(message) {
                   Swal.fire({
                       icon: 'warning',
                       title: 'Opps!',
                       text: message,
                       showConfirmButton: false,
                       timer: 1500
                   });
                   return false;
               },

               // ======================
               // SUBMIT
               // ======================
               validateAndUpdate() {
                   if (!this.validateForm()) return;

                   Swal.fire({
                       title: 'Are you sure?',
                       text: 'You want to update this transaction.',
                       icon: 'warning',
                       showCancelButton: true,
                       confirmButtonText: 'Yes, update it!'
                   }).then(res => {
                       if (res.isConfirmed) {
                           this.isSubmitting = true;
                           this.updateTransaction();
                       }
                   });
               },

               getTransactionPayload() {
                   const summary = this.calculateSummary();

                   return {
                       customer_id: this.customer_id,
                       transaction_date: this.transaction_date,
                       due_date: this.due_date,
                       payment_status: this.payment_status,
                       payment_date: this.payment_date,
                       status: this.status,
                       discount_percent: this.discountPercent,
                       discount_amount: summary.discountAmount,
                       tax_percent: this.taxPercent,
                       tax_amount: summary.taxAmount,
                       additional_fee: this.additionalFee,
                       total_price: summary.totalPayment,
                       notes: this.notes,
                       packages: this.packages.map(p => ({
                           id: p.id,
                           qty: p.qty,
                           unit: p.unit,
                           price: p.price
                       }))
                   };
               },

               updateTransaction() {
                   fetch(`/account/transactions/${this.transaction_id}`, {
                       method: 'PUT',
                       headers: {
                           'Content-Type': 'application/json',
                           'X-CSRF-TOKEN': '{{ csrf_token() }}'
                       },
                       body: JSON.stringify(this.getTransactionPayload())
                   })
                       .then(res => {
                           if (!res.ok) throw new Error('Failed to update transaction');
                           return res.json();
                       })
                       .then(() => {
                           Swal.fire({
                               icon: 'success',
                               title: 'Success!',
                               text: 'Transaction updated successfully',
                               showConfirmButton: false,
                               timer: 1500
                           }).then(() => {
                               window.location.href = '{{ route("account.transactions.index") }}';
                           });
                       })
                       .catch(err => {
                           this.isSubmitting = false;
                           Swal.fire({
                               icon: 'error',
                               title: 'Error!',
                               text: err.message
                           });
                       });
               }
           }
       }
   </script>
@endsection
