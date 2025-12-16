<div x-data x-show="$store.deleteModal.show" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     @keydown.escape.window="$store.deleteModal.close()">
   <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4" @click.outside="$store.deleteModal.close()">
      <h2 class="text-lg font-bold text-gray-800 mb-2">Delete Confirmation</h2>
      <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete this item? This action cannot be undone.</p>

      <div class="flex justify-end space-x-2">
         <button @click="$store.deleteModal.close()"
                 class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
            Cancel
         </button>

         <form x-bind:action="$store.deleteModal.url" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
               Delete
            </button>
         </form>
      </div>
   </div>
</div>
