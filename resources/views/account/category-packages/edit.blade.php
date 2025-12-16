@extends('layouts.account')

@section('title', 'Edit Package Category')

@section('content')

   <!-- Header -->
   <div x-data class="mb-8">

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Edit Package Category</h2>
            <p class="text-gray-600 mt-1 italic">Update the details of your package category</p>
         </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.category-packages.update', $category_package->id) }}">
               @csrf
               @method('PUT')

               <div class="space-y-4">
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Name</label>
                     <input type="text" name="name" value="{{ old('name', $category_package->name) }}" placeholder="Category Name" class="mt-1 block w-full border border-gray-300 @error('name') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('name')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Description</label>
                     <textarea name="description" placeholder="Description (optional)" class="mt-1 block w-full border border-gray-300 @error('description') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary" rows="3">{{ old('description', $category_package->description) }}</textarea>
                     @error('description')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>
               </div>

               <div class="mt-6 flex justify-start">
                  <a href="{{ route('account.category-packages.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                  <button type="submit" class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Update</button>
               </div>
            </form>

         </div>
      </div>

   </div>
@endsection
