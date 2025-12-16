@extends('layouts.account')

@section('title', 'Create Customer')

@section('content')

   <!-- Header -->
   <div x-data="{ showAddModal: false, showEditModal: false, selectedCustomer: {} }" class="mb-8">

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Create Customer</h2>
            <p class="text-gray-600 mt-1 italic">Create a new customer and assign to an outlet</p>
         </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.customers.store') }}">
               @csrf

               <div class="space-y-4">
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Name</label>
                     <input type="text" name="name" value="{{ old('name') }}" placeholder="Customer Name"
                            class="mt-1 block w-full border border-gray-300 @error('name') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('name')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Office Name</label>
                     <input type="text" name="office_name" value="{{ old('office_name') }}" placeholder="Office Name"
                            class="mt-1 block w-full border border-gray-300 @error('name') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('office_name')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Email Address</label>
                     <input type="email" name="email" value="{{ old('name') }}" placeholder="Email Address"
                            class="mt-1 block w-full border border-gray-300 @error('email') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('email')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Phone</label>
                     <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone Number"
                            class="mt-1 block w-full border border-gray-300 @error('phone') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('phone')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Address</label>
                     <textarea name="address" placeholder="Customer Address"
                               class="mt-1 block w-full border border-gray-300 @error('address') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary"
                               rows="2">{{ old('address') }}</textarea>
                     @error('address')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  @can('admin')
                     <div>
                        <label class="block text-sm font-medium text-gray-700">Outlet</label>
                        <select name="outlet_id"
                                class="mt-1 block w-full border border-gray-300 @error('outlet_id') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
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
                  @endcan
               </div>

               <div class="mt-6 flex justify-start">
                  <a href="{{ route('account.customers.index') }}"
                     class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                  <button type="submit"
                          class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Save
                  </button>
               </div>
            </form>

         </div>
      </div>

   </div>
@endsection
