@extends('layouts.account')

@section('title', 'Edit Package')

@section('content')

   <!-- Header -->
   <div x-data class="mb-8">

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Edit Package</h2>
            <p class="text-gray-600 mt-1 italic">Update your service package information</p>
         </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.packages.update', $package) }}">
               @csrf
               @method('PUT')

               <div class="space-y-4">

                  @can('admin')
                     <!-- Outlet -->
                     <div>
                        <label class="block text-sm font-medium text-gray-700">Outlet</label>
                        <select name="outlet_id" class="mt-1 block w-full border border-gray-300 @error('outlet_id') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                           <option value="">Select Outlet</option>
                           @foreach ($outlets as $outlet)
                              <option value="{{ $outlet->id }}" {{ old('outlet_id', $package->outlet_id) == $outlet->id ? 'selected' : '' }}>
                                 {{ $outlet->name }}
                              </option>
                           @endforeach
                        </select>
                        @error('outlet_id')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                     </div>
                  @endcan

                  <!-- Category Package -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Category Package</label>
                     <select name="category_package_id" class="mt-1 block w-full border border-gray-300 @error('category_package_id') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                           <option value="{{ $category->id }}" {{ old('category_package_id', $package->category_package_id) == $category->id ? 'selected' : '' }}>
                              {{ $category->name }}
                           </option>
                        @endforeach
                     </select>
                     @error('category_package_id')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <!-- Name -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Name</label>
                     <input type="text" name="name" value="{{ old('name', $package->name) }}" placeholder="Package Name" class="mt-1 block w-full border border-gray-300 @error('name') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('name')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <!-- Price -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Price</label>
                     <input type="number" name="price" value="{{ old('price', $package->price) }}" placeholder="Package Price" class="mt-1 block w-full border border-gray-300 @error('price') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('price')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>
               </div>

               <div class="mt-6 flex justify-start">
                  <a href="{{ route('account.packages.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                  <button type="submit" class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Update</button>
               </div>
            </form>

         </div>
      </div>

   </div>
@endsection
