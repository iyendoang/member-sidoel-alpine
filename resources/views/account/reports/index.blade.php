@extends('layouts.account')

@section('title', 'Financial Report')

@section('content')
   <div class="mb-8">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">

         <div>
            <h2 class="text-2xl font-bold text-gray-900">Financial Report</h2>
            <p class="text-gray-500 mt-1 italic">Revenue and expenses analysis</p>
         </div>

         @if(request()->has('start_date') && request()->has('end_date'))
            <div class="mt-4 sm:mt-0">
               <a href="{{ route('account.reports.export') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&outlet_id={{ request('outlet_id') }}"
                  class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl py-2.5 px-4 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow">
                  <x-icons.download width="20" height="20" class="mr-2"/>
                  Export Report
               </a>
            </div>
         @endif

      </div>


      <!-- Filter Form -->
      <div class="bg-white rounded-xl shadow p-6 mb-8 ">
         <form action="{{ route('account.reports.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
               <!-- Outlet Selection -->
               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1.5">Outlet</label>
                  <select name="outlet_id"
                          class="mt-1 block w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 border bg-white transition duration-150 ease-in-out">
                     <option value="">All Outlets</option>
                     @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ request('outlet_id') == $outlet->id ? 'selected' : '' }}>
                           {{ $outlet->name }}
                        </option>
                     @endforeach
                  </select>
               </div>

               <!-- Date Range -->
               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
                  <input type="date" name="start_date"
                         value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}"
                         class="mt-1 block w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 border bg-white transition duration-150 ease-in-out">
               </div>

               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
                  <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                         class="mt-1 block w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 border bg-white transition duration-150 ease-in-out">
               </div>

               <!-- Submit Button -->
               <div class="flex items-end">
                  <button type="submit"
                          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl py-2.5 px-4 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow">
                     Generate Report
                  </button>
               </div>
            </div>
         </form>
      </div>

      @if(request()->has('start_date'))
         <!-- Summary Cards -->
         <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <!-- Revenue Card -->
            <div class="bg-white rounded-xl shadow p-6">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-base font-medium text-gray-500">Total Revenue</h3>
                     <p class="mt-1 text-3xl font-semibold text-blue-600">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                  </div>
                  <div class="bg-blue-50 rounded-lg p-3">
                     <x-icons.coins width="24" height="24" class="text-blue-600"/>
                  </div>
               </div>
               <p class="mt-3 text-sm text-gray-500">
                  {{ $selectedOutlet ? $selectedOutlet->name : 'All Outlets' }}
               </p>
            </div>

            <!-- Expenses Card -->
            <div class="bg-white rounded-xl shadow p-6">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-base font-medium text-gray-500">Total Expenses</h3>
                     <p class="mt-1 text-3xl font-semibold text-orange-600">
                        Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                  </div>
                  <div class="bg-orange-50 rounded-lg p-3">
                     <x-icons.coins width="24" height="24" class="text-orange-600"/>
                  </div>
               </div>
            </div>

            <!-- Profit Card -->
            <div class="bg-white rounded-xl shadow p-6">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-base font-medium text-gray-500">Net Profit</h3>
                     <p class="mt-1 text-3xl font-semibold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($profit, 0, ',', '.') }}
                     </p>
                  </div>
                  <div class="{{ $profit >= 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-lg p-3">
                     <x-icons.coins width="24" height="24"
                                    class="{{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}"/>
                  </div>
               </div>
            </div>
         </div>

         <!-- Financial Table -->
         <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-lg font-semibold text-gray-900">Daily Financial Report</h3>
                     <p class="text-sm text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                        - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                     </p>
                  </div>
               </div>
            </div>

            <div class="overflow-x-auto">
               <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                  <tr>
                     <th scope="col"
                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                     </th>
                     <th scope="col"
                         class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue
                     </th>
                     <th scope="col"
                         class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Expenses
                     </th>
                     <th scope="col"
                         class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                     </th>
                  </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                  @php
                     $currentDate = \Carbon\Carbon::parse($startDate);
                     $endDate = \Carbon\Carbon::parse($endDate);
                  @endphp

                  @while($currentDate <= $endDate)
                     @php
                        $dateString = $currentDate->format('Y-m-d');
                        $revenue = $revenueData->firstWhere('date', $dateString);
                        $expense = $expensesData->firstWhere('date', $dateString);
                        $dayTotal = ($revenue->total ?? 0) - ($expense->total ?? 0);
                     @endphp
                     <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                           {{ $currentDate->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                           Rp {{ number_format($revenue->total ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                           Rp {{ number_format($expense->total ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right {{ $dayTotal >= 0 ? 'text-green-600' : 'text-red-600' }}">
                           Rp {{ number_format($dayTotal, 0, ',', '.') }}
                        </td>
                     </tr>
                     @php
                        $currentDate->addDay();
                     @endphp
                  @endwhile
                  </tbody>
               </table>
            </div>
         </div>
      @endif
   </div>
@endsection
