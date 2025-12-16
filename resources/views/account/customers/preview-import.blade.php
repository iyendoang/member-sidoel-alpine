@extends('layouts.account')

@section('title', 'Import & Export Customers')

@section('content')
   <div class="max-w-6xl mx-auto">

      {{-- 1. TAMPILKAN LIST ERROR DETAIL (Jika Ada) --}}
      @if(session('import_errors'))
         <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg animate-fade-in-down">
            <div class="flex">
               <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                     <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
               </div>
               <div class="ml-3">
                  <h3 class="text-sm font-bold text-red-800">
                     {{ session('warning') ?? 'Terdapat kesalahan pada data import:' }}
                  </h3>
                  <div class="mt-2 text-sm text-red-700 max-h-40 overflow-y-auto">
                     <ul role="list" class="list-disc pl-5 space-y-1">
                        @foreach(session('import_errors') as $error)
                           <li>{{ $error }}</li>
                        @endforeach
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      @endif

      {{-- 2. TAMPILKAN SUKSES (Jika Sukses Total) --}}
      @if(session('success'))
         <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg animate-fade-in-down">
            <div class="flex">
               <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                     <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
               </div>
               <div class="ml-3">
                  <p class="text-sm font-medium text-emerald-800">
                     {{ session('success') }}
                  </p>
               </div>
            </div>
         </div>
      @endif

      {{-- 3. TAMPILKAN ERROR SISTEM GENERAL --}}
      @if(session('error'))
         <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex">
               <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                     <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
               </div>
               <div class="ml-3">
                  <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
               </div>
            </div>
         </div>
      @endif

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
         <div>
            <h2 class="text-2xl font-bold text-gray-900">Manage Data Customers</h2>
            <p class="text-gray-600 mt-1">Export data pelanggan atau Import data baru dari Excel.</p>
         </div>
         <a href="{{ route('account.customers.index') }}" class="mt-4 sm:mt-0 text-sm text-gray-500 hover:text-gray-700 flex items-center">
            &larr; Kembali ke Daftar
         </a>
      </div>

      {{-- ... (Sisa kode Anda untuk grid Export & Form Import tetap sama) ... --}}

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

         <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
               <div class="flex items-start justify-between mb-4">
                  <div>
                     <h3 class="text-lg font-semibold text-gray-800">Export Data</h3>
                     <p class="text-sm text-gray-500 mt-1">Download semua data customer (format .xlsx).</p>
                  </div>
                  <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                     <x-icons.download width="24" height="24"/>
                  </div>
               </div>
            </div>
            <a href="{{ route('account.customers.export') }}"
               class="inline-flex justify-center w-full px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
               Download Excel
            </a>
         </div>

         <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-start justify-between mb-4">
               <div>
                  <h3 class="text-lg font-semibold text-gray-800">Import Data</h3>
                  <p class="text-sm text-gray-500 mt-1">Upload file Excel untuk menambah/update data.</p>
               </div>
               <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                  <x-icons.arrows-sort width="24" height="24"/>
               </div>
            </div>

            <form action="{{ route('account.customers.import-preview') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  x-data="{ loading: false }"
                  @submit="loading = true">
               @csrf

               @if(isset($outlets) && count($outlets) > 0)
                  @can('admin')
                     <div class="mb-3">
                        <select name="outlet_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                           <option value="">-- Gunakan Outlet Saya / Default --</option>
                           @foreach($outlets as $outlet)
                              <option value="{{ $outlet->id }}" {{ (old('outlet_id') ?? ($targetOutletId ?? '')) == $outlet->id ? 'selected' : '' }}>
                                 {{ $outlet->name }}
                              </option>
                           @endforeach
                        </select>
                     </div>
                  @endcan
               @endif

               <div class="flex gap-2">
                  <input type="file" name="file" required accept=".xlsx,.xls"
                         class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

                  <button type="submit" :disabled="loading"
                          class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 flex items-center shadow-sm whitespace-nowrap">
                     <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                     Check File
                  </button>
               </div>
            </form>
         </div>
      </div>

      {{-- ... (Bagian Preview Data - Tetap Sama) ... --}}
      @if(isset($previewData) && count($previewData) > 0)

         <div class="animate-fade-in-up border-t border-gray-200 pt-8">
            <div class="flex items-center justify-between mb-6">
               <h2 class="text-xl font-bold text-gray-900">Preview Hasil</h2>
               <div class="flex gap-3">
                  <div class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                     <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                     Insert: {{ $previewData->where('status', 'INSERT')->count() }}
                  </div>
                  <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                     <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                     Update: {{ $previewData->where('status', 'UPDATE')->count() }}
                  </div>
               </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200 mb-6">
               <div class="overflow-x-auto max-h-[500px]">
                  <table class="min-w-full divide-y divide-gray-200 relative">
                     <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                     <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Office</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                     </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200">
                     @foreach($previewData as $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                           <td class="px-6 py-4 whitespace-nowrap">
                              @if($row['status'] === 'INSERT')
                                 <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-emerald-100 text-emerald-800">
                              NEW INSERT
                           </span>
                              @else
                                 <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-blue-100 text-blue-800">
                              UPDATE
                           </span>
                              @endif
                           </td>
                           <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $row['name'] }}</td>
                           <td class="px-6 py-4 text-sm text-gray-500">{{ $row['email'] }}</td>
                           <td class="px-6 py-4 text-sm text-gray-500">{{ $row['office_name'] }}</td>
                           <td class="px-6 py-4 text-sm text-gray-500">{{ $row['phone'] }}</td>
                        </tr>
                     @endforeach
                     </tbody>
                  </table>
               </div>
            </div>

            {{-- Di bagian bawah file preview-import.blade.php --}}

            <div class="flex justify-end gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200">
               <a href="{{ route('account.customers.import-view') }}"
                  class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                  Cancel / Reset
               </a>

               <form action="{{ route('account.customers.process-import') }}" method="POST">
                  @csrf

                  {{-- UBAH INI: Menggunakan staging_file dari Controller Preview --}}
                  <input type="hidden" name="staging_file" value="{{ $stagingFile }}">

                  {{-- Outlet ID sudah tertanam di dalam JSON, jadi field ini opsional,
                       tapi boleh dibiarkan jika logic controller membutuhkannya untuk validasi lain --}}
                  <input type="hidden" name="outlet_id" value="{{ $targetOutletId ?? '' }}">

                  <button type="submit"
                          class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-md flex items-center transition-transform active:scale-95">
                     <x-icons.circle-check class="w-4 h-4 mr-2" width="20" height="20"/>
                     Confirm & Process Import
                  </button>
               </form>
            </div>
         </div>
      @endif

   </div>
@endsection