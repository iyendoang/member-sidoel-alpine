<div x-data x-show="$store.unitCreateModal.show" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     @keydown.escape.window="$store.unitCreateModal.close()">

   <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4"
        @click.outside="$store.unitCreateModal.close()">

      <h2 class="text-lg font-bold text-gray-800 mb-2">Create Unit</h2>
      <p class="text-sm text-gray-600 mb-4">Add a new unit of measurement</p>

      <form method="POST" action="{{ route('account.units.store') }}">
         @csrf

         <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Unit Name</label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                              focus:ring-primary focus:border-primary
                              @error('name') border-red-500 @enderror">

            @error('name')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
         </div>

         <div class="flex justify-end space-x-2 mt-6">
            <button type="button"
                    @click="$store.unitCreateModal.close()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
               Cancel
            </button>

            <button type="submit"
                    class="px-4 py-2 bg-primary text-white rounded-xl hover:bg-primary/90">
               Save
            </button>
         </div>
      </form>
   </div>
</div>
