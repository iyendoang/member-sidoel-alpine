@extends('layouts.account')

@section('title', 'Dashboard')

@section('content')

   <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
      <div>
         <h2 class="text-xl font-semibold text-gray-900">Hi, {{ auth()->user()->name }}</h2>
         <p class="text-gray-600 mt-1 italic">You are currently managing the outlet: <span
                    class="font-bold text-gray-800">{{ auth()->user()->outlet->name ?? '-' }}</span></p>
      </div>
      @can('admin')
         <div class="mt-2 sm:mt-0 bg-blue-100 text-blue-800 px-3 py-1 rounded-lg text-sm">
            <x-icons.eye class="inline-block mr-1" width="20" height="20"/>
            Admin View: Seeing all outlets data
         </div>
      @endcan
   </div>

   <!-- Stats Grid -->
   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
      <!-- Today's Transactions -->
      <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm">
         <div class="flex items-center justify-between">
            <div>
               <p class="text-sm text-gray-600">Today's Transactions</p>
               <p class="text-2xl font-bold text-gray-900">{{ $todayTransactions }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center">
               <x-icons.cash-register width="24" height="24" class="text-blue-600"/>
            </div>
         </div>
         <div class="mt-4 flex items-center">
            <span class="{{ $transactionPercentage['direction'] == 'up' ? 'text-green-600' : ($transactionPercentage['direction'] == 'down' ? 'text-red-600' : 'text-gray-600') }} text-sm font-medium">
                @if($transactionPercentage['direction'] == 'up')
                  +
               @elseif($transactionPercentage['direction'] == 'down')
                  -
               @endif
               {{ $transactionPercentage['value'] }}%
            </span>
            <span class="text-gray-500 text-sm ml-2">vs yesterday</span>
         </div>
      </div>

      <!-- Today's Revenue -->
      <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm">
         <div class="flex items-center justify-between">
            <div>
               <p class="text-sm text-gray-600">Today's Revenue</p>
               <p class="text-2xl font-bold text-gray-900">Rp. {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center">
               <x-icons.coins width="24" height="24" class="text-green-600"/>
            </div>
         </div>
         <div class="mt-4 flex items-center">
            <span class="{{ $revenuePercentage['direction'] == 'up' ? 'text-green-600' : ($revenuePercentage['direction'] == 'down' ? 'text-red-600' : 'text-gray-600') }} text-sm font-medium">
                @if($revenuePercentage['direction'] == 'up')
                  +
               @elseif($revenuePercentage['direction'] == 'down')
                  -
               @endif
               {{ $revenuePercentage['value'] }}%
            </span>
            <span class="text-gray-500 text-sm ml-2">vs yesterday</span>
         </div>
      </div>

      <!-- Monthly Expenses -->
      <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm">
         <div class="flex items-center justify-between">
            <div>
               <p class="text-sm text-gray-600">30 Days Expenses</p>
               <p class="text-2xl font-bold text-gray-900">
                  Rp. {{ number_format($currentMonthExpenses, 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-xl flex items-center justify-center">
               <x-icons.coins width="24" height="24" class="text-orange-600"/>
            </div>
         </div>
         <div class="mt-4 flex items-center">
            <span class="{{ $expensePercentage['direction'] == 'up' ? 'text-red-600' : ($expensePercentage['direction'] == 'down' ? 'text-green-600' : 'text-gray-600') }} text-sm font-medium">
                @if($expensePercentage['direction'] == 'up')
                  +
               @elseif($expensePercentage['direction'] == 'down')
                  -
               @endif
               {{ $expensePercentage['value'] }}%
            </span>
            <span class="text-gray-500 text-sm ml-2">vs previous 30 days</span>
         </div>
      </div>

      <!-- Total Customers -->
      <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm">
         <div class="flex items-center justify-between">
            <div>
               <p class="text-sm text-gray-600">Total Customers</p>
               <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center">
               <x-icons.users width="24" height="24" class="text-purple-600"/>
            </div>
         </div>
         <div class="mt-4 flex items-center">
            <span class="text-gray-500 text-sm">All time customers</span>
         </div>
      </div>
   </div>

   <!-- Main Content Area -->
   <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Left Column -->
      <div class="lg:col-span-2 space-y-6">

         <!-- Revenue Chart -->
         <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6 border-b-2 border-gray-300 pb-4">
               <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Revenue Last 30 Days</h3>
               <div class="flex items-center space-x-4">
                  <div class="flex items-center">
                     <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                     <span class="text-sm text-gray-600">Total: Rp. {{ number_format($revenueChartData['total'], 0, ',', '.') }}</span>
                  </div>
                  <div class="flex items-center">
                     <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                     <span class="text-sm text-gray-600">Avg: Rp. {{ number_format($revenueChartData['average'], 0, ',', '.') }}/day</span>
                  </div>
               </div>
            </div>
            <div class="relative h-80">
               <canvas id="revenueChart"></canvas>
            </div>
         </div>

         <!-- Weekly Comparison -->
         <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 ">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b-2 border-gray-300 pb-4">Weekly Revenue
               Comparison</h3>
            <div class="relative h-80 ">
               <canvas id="weeklyChart"></canvas>
            </div>
         </div>

      </div>

      <!-- Right Column -->
      <div class="space-y-6">

         <!-- Status Distribution -->
         <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b-2 border-gray-300 pb-4">Transaction Status
               (Last 30 Days)</h3>
            <div class="relative h-64">
               <canvas id="statusChart"></canvas>
            </div>
         </div>

         <!-- Recent Customers -->
         <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b-2 border-gray-300 pb-4">Recent Customers</h3>
            <div class="space-y-4">
               @foreach($recentCustomers as $customer)
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:shadow transition-colors">
                     <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                           <span class="text-purple-600 font-medium">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                        </div>
                        <div>
                           <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                           <p class="text-sm text-gray-500">Joined {{ $customer->created_at->diffForHumans() }}</p>
                        </div>
                     </div>
                     <div class="text-right">
                        @if($customer->transactions->count() > 0)
                           <p class="text-sm font-medium text-gray-900">
                              Rp. {{ number_format($customer->transactions->first()->total_price, 0, ',', '.') }}</p>
                           <p class="text-xs text-gray-500">{{ $customer->transactions->first()->created_at->format('M d') }}</p>
                        @else
                           <span class="text-xs text-gray-400">No transactions</span>
                        @endif
                     </div>
                  </div>
               @endforeach
            </div>
         </div>

      </div>
   </div>

   <!-- Bottom Row -->
   <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

      <!-- Popular Packages -->
      <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
         <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b-2 border-gray-300 pb-4">Top 5 Packages (Last 30
            Days)</h3>
         <div class="relative h-64">
            <canvas id="packagesChart"></canvas>
         </div>
      </div>

      <!-- Recent Transactions -->
      <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
         <div class="flex items-center justify-between mb-6 border-b-2 border-gray-300 pb-4">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Recent Transactions</h3>
            <a href="/account/transactions" class="text-primary hover:text-primary/80 text-sm font-medium">See All</a>
         </div>
         <div class="space-y-4">
            @foreach($recentTransactions as $transaction)
               <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:shadow transition-colors">
                  <div class="flex items-center space-x-4">
                     <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">{{ strtoupper(substr($transaction->customer->name, 0, 1)) }}</span>
                     </div>
                     <div>
                        <p class="font-medium text-gray-900">{{ $transaction->customer->name }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}
                           • {{ $transaction->transaction_details()->count() }} items</p>
                     </div>
                  </div>
                  <div class="text-right">
                     <p class="font-semibold text-gray-900">
                        Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                     @php
                        $statusClasses = [
                            'NEW' => 'bg-blue-100 text-blue-800',
                            'IN PROGRESS' => 'bg-yellow-100 text-yellow-800',
                            'COMPLETED' => 'bg-green-100 text-green-800',
                            'CANCELLED' => 'bg-red-100 text-red-800',
                        ];
                        $statusText = strtoupper($transaction->status);
                        $badgeClass = $statusClasses[$statusText] ?? 'bg-gray-100 text-gray-800';
                     @endphp
                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ ucwords(strtolower($transaction->status)) }}
                        </span>
                  </div>
               </div>
            @endforeach
         </div>
      </div>

   </div>

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script>
       document.addEventListener('DOMContentLoaded', function () {
           // Revenue Chart
           const revenueCtx = document.getElementById('revenueChart').getContext('2d');
           const revenueChart = new Chart(revenueCtx, {
               type: 'bar',
               data: {
                   labels: @json($revenueChartData['labels']),
                   datasets: [{
                       label: 'Daily Revenue',
                       data: @json($revenueChartData['data']),
                       backgroundColor: '#3B82F6',
                       borderColor: '#3B82F6',
                       borderWidth: 1,
                       borderRadius: 4,
                       barPercentage: 0.8,
                       categoryPercentage: 0.9
                   }]
               },
               options: {
                   responsive: true,
                   maintainAspectRatio: false,
                   plugins: {
                       legend: {display: false},
                       tooltip: {
                           callbacks: {
                               label: function (context) {
                                   return 'Rp. ' + context.raw.toLocaleString('id-ID');
                               }
                           }
                       }
                   },
                   scales: {
                       y: {
                           beginAtZero: true,
                           ticks: {
                               callback: function (value) {
                                   return 'Rp. ' + value.toLocaleString('id-ID');
                               }
                           },
                           grid: {drawBorder: false, color: '#E5E7EB'}
                       },
                       x: {grid: {display: false, drawBorder: false}}
                   }
               }
           });

           // Status Distribution Chart
           const statusCtx = document.getElementById('statusChart').getContext('2d');
           const statusChart = new Chart(statusCtx, {
               type: 'doughnut',
               data: {
                   labels: @json($statusDistribution->keys()),
                   datasets: [{
                       data: @json($statusDistribution->values()),
                       backgroundColor: ['#3B82F6', '#F59E0B', '#10B981', '#EF4444'],
                       borderWidth: 0
                   }]
               },
               options: {
                   responsive: true,
                   maintainAspectRatio: false,
                   cutout: '70%',
                   plugins: {
                       legend: {position: 'right'},
                       tooltip: {
                           callbacks: {
                               label: function (context) {
                                   return `${context.label}: ${context.raw} transactions`;
                               }
                           }
                       }
                   }
               }
           });

           // Weekly Comparison Chart
           const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
           const weeklyChart = new Chart(weeklyCtx, {
               type: 'line',
               data: {
                   labels: @json($weeklyComparison['labels']),
                   datasets: [
                       {
                           label: 'This Week',
                           data: @json($weeklyComparison['current']),
                           borderColor: '#3B82F6',
                           backgroundColor: 'rgba(59, 130, 246, 0.1)',
                           tension: 0.3,
                           fill: true
                       },
                       {
                           label: 'Last Week',
                           data: @json($weeklyComparison['previous']),
                           borderColor: '#94A3B8',
                           backgroundColor: 'rgba(148, 163, 184, 0.1)',
                           borderDash: [5, 5],
                           tension: 0.3,
                           fill: true
                       }
                   ]
               },
               options: {
                   responsive: true,
                   maintainAspectRatio: false,
                   plugins: {
                       tooltip: {
                           callbacks: {
                               label: function (context) {
                                   return context.dataset.label + ': Rp. ' + context.raw.toLocaleString('id-ID');
                               }
                           }
                       }
                   },
                   scales: {
                       y: {
                           beginAtZero: true,
                           ticks: {
                               callback: function (value) {
                                   return 'Rp. ' + value.toLocaleString('id-ID');
                               }
                           }
                       }
                   }
               }
           });

           // Packages Chart
           const packagesCtx = document.getElementById('packagesChart').getContext('2d');
           const packagesChart = new Chart(packagesCtx, {
               type: 'bar',
               data: {
                   labels: @json($popularPackages->map(fn($item) => $item->package_name . ' - (' . $item->outlet_name . ')')),
                   datasets: [{
                       label: 'Quantity Sold',
                       data: @json($popularPackages->pluck('total_quantity')),
                       backgroundColor: '#8B5CF6',
                       borderRadius: 4
                   }]
               },
               options: {
                   indexAxis: 'y',
                   responsive: true,
                   maintainAspectRatio: false,
                   plugins: {legend: {display: false}},
                   scales: {
                       x: {beginAtZero: true, grid: {drawBorder: false}},
                       y: {grid: {display: false, drawBorder: false}}
                   }
               }
           });

       });
   </script>
@endsection
