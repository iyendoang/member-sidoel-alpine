@extends('layouts.account')

@section('title', 'Units')

@section('content')

   <div class="mb-8" x-data>
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
         <div>
            <h2 class="text-xl font-bold text-gray-900">Unit Management</h2>
            <p class="text-gray-600 mt-1 italic">Manage your units of measurement</p>
         </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
         <!-- Table Header -->
         <div class="px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">

               {{-- CREATE --}}
               @can('units.create')
                  <button
                          @click="$store.modal.open({
                        title: 'Create Unit',
                        subtitle: 'Add new unit of measurement'
                    })"
                          class="inline-flex text-sm items-center px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                     <x-icons.plus class="mr-1" width="20" height="20"/>
                     Add New
                  </button>
               @endcan

               {{-- SEARCH --}}
               <div class="block relative">
                  <form action="{{ route('account.units.index') }}">
                     <input
                             type="text"
                             name="search"
                             placeholder="Search..."
                             value="{{ request('search') }}"
                             class="w-full text-sm bg-gray-100 pl-10 pr-4 py-2 border border-gray-300 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icons.search class="text-gray-400" width="20" height="20"/>
                     </div>
                  </form>
               </div>
            </div>
         </div>

         <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-gray-200">
               <thead class="bg-gray-50">
               <tr>
                  {{-- NO --}}
                  <th
                          class="w-16 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                     No.
                  </th>

                  {{-- NAME --}}
                  <th
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                     Unit Name
                  </th>

                  {{-- ACTION --}}
                  <th
                          class="w-24 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                     Actions
                  </th>
               </tr>
               </thead>

               <tbody class="bg-white divide-y divide-gray-200">
               @foreach($units as $unit)
                  <tr class="hover:bg-gray-50">
                     {{-- NO --}}
                     <td class="px-4 py-3 text-sm text-center text-gray-900">
                        {{ $loop->iteration }}
                     </td>

                     {{-- NAME --}}
                     <td class="px-6 py-3 text-sm text-gray-900 truncate">
                        {{ $unit->name }}
                     </td>

                     {{-- ACTION --}}
                     <td class="px-4 py-3">
                        <div class="flex justify-center items-center gap-3">
                           {{-- EDIT --}}
                           @can('units.edit')
                              <button
                                      @click="$store.modal.open({
                           title: 'Edit Unit',
                           subtitle: 'Update unit data',
                           data: {
                              id: {{ $unit->id }},
                              name: '{{ $unit->name }}'
                           }
                        })"
                                      class="text-primary hover:text-primary/90">
                                 <x-icons.pencil width="18" height="18"/>
                              </button>
                           @endcan

                           {{-- DELETE --}}
                           @can('units.delete')
                              <button
                                      @click="$store.deleteModal.open('{{ route('account.units.destroy', $unit->id) }}')"
                                      class="text-red-500 hover:text-red-600">
                                 <x-icons.trash width="18" height="18"/>
                              </button>
                           @endcan
                        </div>
                     </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
      </div>

      <!-- Pagination -->
      <div class="mt-5">
         {{ $units->links('vendor.pagination.tailwind') }}
      </div>

      {{-- GENERIC MODAL --}}
      <x-modal>

         {{-- CREATE FORM --}}
         <template x-if="$store.modal.title === 'Create Unit'">
            <form method="POST" action="{{ route('account.units.store') }}">
               @csrf

               <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Unit Name</label>
                  <input
                          type="text"
                          name="name"
                          class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:ring-primary focus:border-primary">
               </div>

               <div class="flex justify-end gap-2">
                  <button type="button"
                          @click="$store.modal.close()"
                          class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl">
                     Cancel
                  </button>
                  <button type="submit"
                          class="px-4 py-2 bg-primary text-white rounded-xl">
                     Save
                  </button>
               </div>
            </form>
         </template>

         {{-- EDIT FORM --}}
         <template x-if="$store.modal.title === 'Edit Unit'">
            <form
                    method="POST"
                    :action="`/account/units/${$store.modal.data.id}`"
            >
               @csrf
               @method('PUT')

               <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Unit Name</label>
                  <input
                          type="text"
                          name="name"
                          x-model="$store.modal.data.name"
                          class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                       focus:ring-primary focus:border-primary">
               </div>

               <div class="flex justify-end gap-2">
                  <button
                          type="button"
                          @click="$store.modal.close()"
                          class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl">
                     Cancel
                  </button>

                  <button
                          type="submit"
                          class="px-4 py-2 bg-primary text-white rounded-xl">
                     Update
                  </button>
               </div>
            </form>
         </template>
      </x-modal>

      {{-- DELETE MODAL --}}
      <x-modal-delete/>

   </div>

@endsection
