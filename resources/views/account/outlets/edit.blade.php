@extends('layouts.account')

@section('title', 'Edit Outlet')

@section('content')
   <!-- Header -->
   <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Edit Outlet</h2>
            <p class="text-gray-600 mt-1 italic">Update outlet information</p>
         </div>
      </div>

      <!-- Form Section -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.outlets.update', $outlet->id) }}">
               @csrf
               @method('PUT')

               <div class="space-y-4">
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Code Outlet</label>
                     <input type="text" name="code_outlet" value="{{ old('code_outlet', $outlet->code_outlet) }}"
                            placeholder="Code Outlet"
                            class="mt-1 block w-full border border-gray-300 @error('code_outlet') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('code_outlet')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Name</label>
                     <input type="text" name="name" value="{{ old('name', $outlet->name) }}" placeholder="Name"
                            class="mt-1 block w-full border border-gray-300 @error('name') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('name')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">Address</label>
                     <textarea name="address" placeholder="Address"
                               class="mt-1 block w-full border border-gray-300 @error('address') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary"
                               rows="2">{{ old('address', $outlet->address) }}</textarea>
                     @error('address')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">No. Phone</label>
                     <input type="text" name="phone" value="{{ old('phone', $outlet->phone) }}" placeholder="No. Phone"
                            class="mt-1 block w-full border border-gray-300 @error('phone') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                     @error('phone')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

                  <!-- Notes -->
                  <div>
                     <label class="block text-sm font-medium text-gray-700">Notes</label>

                     <!-- Quill editor container -->
                     <div id="editor" class="mt-1 border border-gray-300 rounded-lg" style="min-height: 150px;">
                        {!! old('notes', $outlet->notes) !!}
                     </div>

                     <!-- Hidden input to store content -->
                     <input type="hidden" name="notes" value="{{ old('notes', $outlet->notes) }}">

                     @error('notes')
                     <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                     @enderror
                  </div>

               </div>

               <div class="mt-6 flex justify-start">
                  <a href="{{ route('account.outlets.index') }}"
                     class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                  <button type="submit"
                          class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Update
                  </button>
               </div>
            </form>

         </div>
      </div>
   </div>

   <!-- Inisialisasi Quill -->
   <script>
       document.addEventListener('DOMContentLoaded', () => {
           if (typeof initQuill === 'function') {
               initQuill('#editor', 'input[name=notes]');
           }
       });
   </script>
@endsection
