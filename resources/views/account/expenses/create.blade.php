@extends('layouts.account')

@section('title', 'Create Expense')

@section('content')

   <!-- Header -->
   <div x-data class="mb-8">

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Create Expense</h2>
            <p class="text-gray-600 mt-1 italic">Add a new expense to track your financials</p>
         </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.expenses.store') }}">
               @csrf

               <div class="space-y-4">
                  @can('admin')
                     <div>
                        <label class="block text-sm font-medium text-gray-700">Outlet</label>
                        <select name="outlet_id" class="mt-1 block w-full border border-gray-300 @error('outlet_id') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                           <option value="">-- Select Outlet --</option>
                           @foreach ($outlets as $outlet)
                              <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>{{ $outlet->name }}</option>
                           @endforeach
                        </select>
                        @error('outlet_id')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                     </div>
                  @endcan

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Category</label>
                     <select name="category_expense_id" class="mt-1 block w-full border border-gray-300 @error('category_expense_id') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                           <option value="{{ $category->id }}" {{ old('category_expense_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                     </select>
                     @error('category_expense_id')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Amount</label>
                     <input type="number" name="amount" value="{{ old('amount') }}" placeholder="Amount" class="mt-1 block w-full border border-gray-300 @error('amount') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('amount')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Description</label>
                     <textarea name="description" placeholder="Description (optional)" class="mt-1 block w-full border border-gray-300 @error('description') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary" rows="3">{{ old('description') }}</textarea>
                     @error('description')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Expense Date</label>
                     <input type="date" name="expense_date" value="{{ old('expense_date') }}" class="mt-1 block w-full border border-gray-300 @error('expense_date') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('expense_date')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>
               </div>

               <div class="mt-6 flex justify-start">
                  <a href="{{ route('account.expenses.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                  <button type="submit" class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Save</button>
               </div>
            </form>

         </div>
      </div>

   </div>
@endsection
