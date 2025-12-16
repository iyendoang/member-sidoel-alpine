<div
        x-data
        x-show="$store.modal.show"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        @keydown.escape.window="$store.modal.close()">
   <div
           class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4"
           @click.outside="$store.modal.close()">
      <!-- Header -->
      <div class="mb-4">
         <h2 class="text-lg font-bold text-gray-800"
             x-text="$store.modal.title"></h2>

         <p class="text-sm text-gray-600"
            x-text="$store.modal.subtitle"></p>
      </div>
      <!-- SLOT CONTENT -->
      {{ $slot }}
   </div>
</div>
