@extends('layouts.account')

@section('title', 'Transactions')

@section('content')

   <div class="mb-8" x-data="transactionForm()" x-init="$watch('packages', () => calculateSummary(), { deep: true })">

      <!-- Header Section -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Create Transaction</h2>
            <p class="text-gray-600 mt-1 italic">Add a new transaction</p>
         </div>
      </div>

      <!-- Outlet Warning -->
      @if(auth()->user()->outlet_id == NULL)
         <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mt-6" role="alert">
            <strong class="font-bold">Warning!</strong>
            <span class="block sm:inline">You need to add an outlet first before you can create a transaction.</span>
         </div>

      @else

         <!-- Main Form Container -->
         <div class="bg-white rounded-xl shadow overflow-hidden mt-5">
            <div class="p-6 max-w-7xl mx-auto">
               <div class="space-y-6">

                  <!-- Customer and Date Information -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                     <div>
                        <label class="block font-medium mb-1">Customer</label>
                        <select class="w-full border border-gray-200 rounded-xl px-3 py-2" x-model="customer_id"
                                required>
                           <option value="">~ Select Customer ~</option>
                           @foreach ($customers as $customer)
                              <option value="{{ $customer->id }}">{{ $customer->name  . ' (' . $customer->office_name.')'}}</option>
                           @endforeach
                        </select>
                     </div>
                     <div>
                        <label class="block font-medium mb-1">Date</label>
                        <input
                                type="text"
                                x-ref="transactionDate"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2"
                                placeholder="Select transaction date"
                                required
                        />
                     </div>
                     <div>
                        <label class="block font-medium mb-1">Due Date</label>
                        <input
                                type="text"
                                x-ref="dueDate"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2"
                                placeholder="Select due date"
                        />
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
                                 <td class="px-3 py-2 text-right">
                                    <span x-text="formatCurrency(package.price)" class="text-gray-700"></span>
                                 </td>
                                 <td class="px-3 py-2 text-right">
                                    <span x-text="formatCurrency(package.qty * package.price)"
                                          class="font-medium text-gray-900"></span>
                                 </td>
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
                                    <!-- clickable area to open modal -->
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
                             class="mt-3 bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-700 transition-colors">
                        + Add Package
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
                     @can('status-transactions.create')
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
                     @endcan
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
                                :disabled="payment_status !== 'PAID'"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 disabled:bg-gray-100"
                                placeholder="Select payment date"
                        />
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
                                @click="saveTransaction()"
                                :disabled="packages.some(p => !p.unit)"
                                class="bg-blue-600 text-white px-4 py-2 rounded-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                           Save Transaction
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      @endif
   </div>

   <script>
       function transactionForm() {
           return {
               // =====================
               // Form Data
               // =====================
               packages: [{id: '', name: '', type: '', qty: 1, unit: '', price: 0}],
               customer_id: null,
               transaction_date: null,
               due_date: null,
               payment_status: '',
               payment_date: null,
               status: '',
               notes: '<p>Thank you for entrusting your hosting services to us. We appreciate the opportunity to support your institution.</p>',


               // =====================
               // UI State
               // =====================
               showPackageModal: false,
               selectedPackageIndex: null,
               packageSearchQuery: '',

               // =====================
               // Pricing
               // =====================
               discountPercent: 0,
               taxPercent: 0,
               additionalFee: 0,

               // =====================
               // Data
               // =====================
               packageList: @json($packages),

               transactionPicker: null,
               duePicker: null,
               paymentPicker: null,

               // =====================
               // COMPUTED
               // =====================
               get filteredPackages() {
                   if (!this.packageSearchQuery) {
                       return this.packageList;
                   }

                   return this.packageList.filter(p =>
                       p.name.toLowerCase().includes(this.packageSearchQuery.toLowerCase())
                   );
               },

               // =====================
               // INIT
               // =====================
               initQuill() {
                   const self = this;

                   this.quill = new Quill(this.$refs.quillEditor, {
                       theme: 'snow',
                       placeholder: 'Write notes here...'
                   });

                   // ✅ SET DEFAULT NOTES
                   if (this.notes) {
                       this.quill.root.innerHTML = this.notes;
                   }

                   // Sync ke Alpine
                   this.quill.on('text-change', function () {
                       self.notes = self.quill.root.innerHTML;
                   });
               },

               init() {
                   this.initFlatpickr();
                   this.initQuill(); // ✅ TAMBAHKAN INI

                   this.$watch('payment_status', (value) => {
                       if (value !== 'PAID') {
                           this.payment_date = null;
                           if (this.paymentPicker) {
                               this.paymentPicker.clear();
                           }
                       }
                   });
               },

               // =====================
               // PACKAGE MODAL
               // =====================
               openPackageSearch(index) {
                   this.selectedPackageIndex = index;
                   this.packageSearchQuery = '';
                   this.showPackageModal = true;
               },

               selectPackage(pkg) {
                   if (this.selectedPackageIndex === null) return;

                   const row = this.packages[this.selectedPackageIndex];

                   row.id = pkg.id;
                   row.name = pkg.name;
                   row.type = pkg.type;
                   row.price = pkg.price;

                   this.showPackageModal = false;
                   this.selectedPackageIndex = null;
               },

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
                   this.packages.splice(index, 1);
               },

               updatePackageTotal(index) {
                   if (this.packages[index].qty < 1) {
                       this.packages[index].qty = 1;
                   }
               },

               // =====================
               // FLATPICKR
               // =====================
               initFlatpickr() {
                   const self = this;

                   this.transactionPicker = flatpickr(this.$refs.transactionDate, {
                       enableTime: true,
                       time_24hr: true,
                       dateFormat: "Y-m-d H:i",
                       defaultDate: new Date(),
                       onChange(selectedDates) {
                           if (!selectedDates.length) return;

                           self.transaction_date = selectedDates[0];
                           self.duePicker.set('minDate', selectedDates[0]);

                           if (!self.due_date || self.due_date < selectedDates[0]) {
                               self.due_date = selectedDates[0];
                               self.duePicker.setDate(selectedDates[0], true);
                           }
                       }
                   });

                   this.duePicker = flatpickr(this.$refs.dueDate, {
                       enableTime: true,
                       time_24hr: true,
                       dateFormat: "Y-m-d H:i",
                       onChange(selectedDates) {
                           if (!selectedDates.length) return;

                           if (self.transaction_date && selectedDates[0] < self.transaction_date) {
                               Swal.fire({
                                   icon: 'warning',
                                   title: 'Opps!',
                                   text: 'Due date cannot be earlier than transaction date',
                                   showConfirmButton: false,
                                   timer: 1500
                               });

                               self.duePicker.setDate(self.transaction_date, true);
                               self.due_date = self.transaction_date;
                               return;
                           }

                           self.due_date = selectedDates[0];
                       }
                   });

                   this.paymentPicker = flatpickr(this.$refs.paymentDate, {
                       enableTime: true,
                       time_24hr: true,
                       dateFormat: "Y-m-d H:i",
                       onChange(selectedDates) {
                           if (!selectedDates.length) return;
                           self.payment_date = selectedDates[0];
                       }
                   });
               },

               // =====================
               // PACKAGE SEARCH
               // =====================
               get filteredPackages() {
                   if (!this.packageSearchQuery) return this.packageList;

                   return this.packageList.filter(p =>
                       p.name.toLowerCase().includes(
                           this.packageSearchQuery.toLowerCase()
                       )
                   );
               },

               openPackageSearch(index) {
                   this.selectedPackageIndex = index;
                   this.packageSearchQuery = '';
                   this.showPackageModal = true;
               },

               selectPackage(pkg) {
                   if (this.selectedPackageIndex === null) return;

                   const target = this.packages[this.selectedPackageIndex];
                   target.id = pkg.id;
                   target.name = pkg.name;
                   target.type = pkg.type;
                   target.price = pkg.price;
                   target.qty = 1;

                   this.showPackageModal = false;
                   this.selectedPackageIndex = null;
               },

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
                   this.packages.splice(index, 1);

                   if (this.packages.length === 0) {
                       this.addEmptyPackage();
                   }
               },

               updatePackageTotal(index) {
                   if (this.packages[index].qty < 1) {
                       this.packages[index].qty = 1;
                   }
               },

               // =====================
               // SUMMARY
               // =====================
               calculateSummary() {
                   const grandTotal = this.packages.reduce(
                       (sum, p) => sum + (p.qty * p.price), 0
                   );

                   const discountAmount = (this.discountPercent / 100) * grandTotal;
                   const afterDiscount = grandTotal - discountAmount;
                   const taxAmount = (this.taxPercent / 100) * afterDiscount;
                   const totalPayment = afterDiscount + taxAmount + this.additionalFee;

                   return {grandTotal, discountAmount, taxAmount, totalPayment};
               },

               formatCurrency(value) {
                   return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
               },

               // =====================
               // VALIDATION
               // =====================
               validateForm() {
                   if (!this.customer_id) {
                       Swal.fire({
                           icon: 'warning',
                           title: 'Opps!',
                           text: 'Please select a customer',
                           showConfirmButton: false,
                           timer: 1500
                       });
                       return false;
                   }

                   if (!this.transaction_date || !this.due_date) {
                       Swal.fire({
                           icon: 'warning',
                           title: 'Opps!',
                           text: 'Transaction date & due date are required',
                           showConfirmButton: false,
                           timer: 1500
                       });
                       return false;
                   }

                   if (this.due_date < this.transaction_date) {
                       Swal.fire({
                           icon: 'warning',
                           title: 'Opps!',
                           text: 'Due date cannot be earlier than transaction date',
                           showConfirmButton: false,
                           timer: 1500
                       });
                       return false;
                   }

                   // 🔴 VALIDASI UNIT & PACKAGE
                   for (let i = 0; i < this.packages.length; i++) {
                       const p = this.packages[i];

                       if (!p.id) {
                           Swal.fire({
                               icon: 'warning',
                               title: 'Opps!',
                               text: `Package row ${i + 1} is not selected`,
                               showConfirmButton: false,
                               timer: 1500
                           });
                           return false;
                       }

                       if (!p.unit) {
                           Swal.fire({
                               icon: 'warning',
                               title: 'Opps!',
                               text: `Unit is required on row ${i + 1}`,
                               showConfirmButton: false,
                               timer: 1500
                           });
                           return false;
                       }

                       if (p.qty < 1) {
                           Swal.fire({
                               icon: 'warning',
                               title: 'Opps!',
                               text: `Quantity must be at least 1 on row ${i + 1}`,
                               showConfirmButton: false,
                               timer: 1500
                           });
                           return false;
                       }
                   }

                   return true;
               },

               // =====================
               // SUBMIT
               // =====================
               getTransactionPayload() {
                   const summary = this.calculateSummary();

                   return {
                       customer_id: this.customer_id,
                       transaction_date: this.transaction_date.toISOString(),
                       due_date: this.due_date.toISOString(),
                       payment_status: this.payment_status,
                       payment_date: this.payment_date
                           ? this.payment_date.toISOString()
                           : null,
                       status: this.status,
                       discount_percent: this.discountPercent,
                       discount_amount: summary.discountAmount,
                       tax_percent: this.taxPercent,
                       tax_amount: summary.taxAmount,
                       additional_fee: this.additionalFee,
                       total_price: summary.totalPayment,
                       packages: this.packages,
                       notes: this.notes
                   };
               },

               saveTransaction() {
                   if (!this.validateForm()) return;

                   fetch('{{ route('account.transactions.store') }}', {
                       method: 'POST',
                       headers: {
                           'Content-Type': 'application/json',
                           'X-CSRF-TOKEN': '{{ csrf_token() }}'
                       },
                       body: JSON.stringify(this.getTransactionPayload())
                   })
                       .then(res => {
                           if (!res.ok) throw new Error('Failed');
                           return res.json();
                       })
                       .then(() => {
                           Swal.fire({
                               icon: 'success',
                               title: 'Success!',
                               text: 'Transaction created successfully!',
                               showConfirmButton: false,
                               timer: 1500
                           }).then(() => {
                               window.location.href = '{{ route("account.transactions.index") }}';
                           });
                       })
                       .catch(err => {
                           console.error(err);
                           alert(err.message);
                       });
               }
           }
       }
   </script>

@endsection