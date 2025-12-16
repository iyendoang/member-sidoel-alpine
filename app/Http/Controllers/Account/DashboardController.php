<?php

   namespace App\Http\Controllers\Account;

   use Carbon\Carbon;
   use App\Models\Expense;
   use App\Models\Customer;
   use App\Models\Transaction;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\DB;
   use App\Http\Controllers\Controller;

   class DashboardController extends Controller
   {
      /**
       * index
       *
       * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
       */
      public function index() {
         // Total transaksi hari ini
         $todayTransactions = Transaction::byOutlet()
                                         ->whereDate('created_at', Carbon::today())
                                         ->count();
         // Total transaksi kemarin
         $yesterdayTransactions = Transaction::byOutlet()
                                             ->whereDate('created_at', Carbon::yesterday())
                                             ->count();
         // Persentase perubahan transaksi
         $transactionPercentage = $this->calculatePercentage($todayTransactions, $yesterdayTransactions);
         // Total pemasukan hari ini
         $todayRevenue = Transaction::byOutlet()
                                    ->whereDate('created_at', Carbon::today())
                                    ->where('payment_status', 'PAID')
                                    ->sum('total_price');
         // Total pemasukan kemarin
         $yesterdayRevenue = Transaction::byOutlet()
                                        ->whereDate('created_at', Carbon::yesterday())
                                        ->where('payment_status', 'PAID')
                                        ->sum('total_price');
         // Persentase perubahan pemasukan
         $revenuePercentage = $this->calculatePercentage($todayRevenue, $yesterdayRevenue);
         // Total pengeluaran 30 hari terakhir
         $currentMonthExpenses = Expense::byOutlet()
                                        ->whereBetween('expense_date', [
                                           Carbon::now()->subDays(29)->startOfDay(),
                                           Carbon::now()->endOfDay(),
                                        ])
                                        ->sum('amount');
         // Total pengeluaran 30 hari sebelumnya
         $previousMonthExpenses = Expense::byOutlet()
                                         ->whereBetween('expense_date', [
                                            Carbon::now()->subDays(59)->startOfDay(),
                                            Carbon::now()->subDays(30)->endOfDay(),
                                         ])
                                         ->sum('amount');
         // Persentase perubahan pengeluaran
         $expensePercentage = $this->calculatePercentage($currentMonthExpenses, $previousMonthExpenses);
         // Total customer
         $totalCustomers = Customer::byOutlet()->count();
         // Data chart & statistik tambahan
         $revenueChartData   = $this->getRevenueChartData();
         $statusDistribution = $this->getStatusDistribution();
         $weeklyComparison   = $this->getWeeklyComparison();
         // Ambil customer terbaru beserta 1 transaksi terakhir
         $recentCustomers = $this->getRecentCustomers();
         // Ambil paket terpopuler
         $popularPackages = $this->getPopularPackages();
         // Transaksi terbaru
         $recentTransactions = Transaction::with('customer')
                                          ->byOutlet()
                                          ->latest()
                                          ->take(3)
                                          ->get();

         return view('account.dashboard.index', [
            'todayTransactions'     => $todayTransactions,
            'yesterdayTransactions' => $yesterdayTransactions,
            'transactionPercentage' => $transactionPercentage,
            'todayRevenue'          => $todayRevenue,
            'yesterdayRevenue'      => $yesterdayRevenue,
            'revenuePercentage'     => $revenuePercentage,
            'currentMonthExpenses'  => $currentMonthExpenses,
            'previousMonthExpenses' => $previousMonthExpenses,
            'expensePercentage'     => $expensePercentage,
            'totalCustomers'        => $totalCustomers,
            'revenueChartData'      => $revenueChartData,
            'statusDistribution'    => $statusDistribution,
            'weeklyComparison'      => $weeklyComparison,
            'recentCustomers'       => $recentCustomers,
            'popularPackages'       => $popularPackages,
            'recentTransactions'    => $recentTransactions,
         ]);
      }

      // Hitung persentase perubahan dari data sebelumnya
      private function calculatePercentage($current, $previous) {
         if($previous == 0){
            return [
               'value'     => $current == 0 ? 0 : 100,
               'direction' => $current == 0 ? 'equal' : 'up',
            ];
         }
         $difference = $current-$previous;
         $percentage = ($difference/$previous)*100;

         return [
            'value'     => abs(round($percentage)),
            'direction' => $difference >= 0 ? 'up' : 'down',
         ];
      }

      // Ambil data grafik pemasukan 30 hari terakhir
      private function getRevenueChartData() {
         $startDate = Carbon::now()->subDays(29)->startOfDay();
         $endDate   = Carbon::now()->endOfDay();
         $revenueData = Transaction::byOutlet()
                                   ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
                                   ->where('payment_status', 'PAID')
                                   ->whereDate('created_at', '>=', $startDate->toDateString())
                                   ->groupBy(DB::raw('DATE(created_at)'))
                                   ->orderBy('date')
                                   ->get()
                                   ->keyBy('date');
         $chartData   = [];
         $labels      = [];
         $currentDate = $startDate->copy();
         while($currentDate <= $endDate) {
            $dateKey     = $currentDate->format('Y-m-d');
            $chartData[] = $revenueData[$dateKey]->revenue ?? 0;
            $labels[]    = $currentDate->format('M j');
            $currentDate->addDay();
         }

         return [
            'labels'  => $labels,
            'data'    => $chartData,
            'total'   => array_sum($chartData),
            'average' => array_sum($chartData)/30,
         ];
      }

      // Distribusi status transaksi (30 hari terakhir)
      private function getStatusDistribution() {
         return Transaction::byOutlet()
                           ->select('status', DB::raw('COUNT(*) as count'))
                           ->where('created_at', '>=', now()->subMonth())
                           ->groupBy('status')
                           ->get()
                           ->mapWithKeys(fn($item) => [$item->status => $item->count]);
      }

      // Perbandingan pemasukan minggu ini dan minggu lalu
      private function getWeeklyComparison() {
         $currentWeek = Transaction::byOutlet()
                                   ->selectRaw('DAYOFWEEK(created_at) as day, SUM(total_price) as revenue')
                                   ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                   ->where('payment_status', 'PAID')
                                   ->groupBy('day')
                                   ->orderBy('day')
                                   ->get()
                                   ->pluck('revenue', 'day');
         $lastWeek = Transaction::byOutlet()
                                ->selectRaw('DAYOFWEEK(created_at) as day, SUM(total_price) as revenue')
                                ->whereBetween('created_at', [
                                   now()->subWeek()->startOfWeek(),
                                   now()->subWeek()->endOfWeek(),
                                ])
                                ->where('payment_status', 'PAID')
                                ->groupBy('day')
                                ->orderBy('day')
                                ->get()
                                ->pluck('revenue', 'day');
         $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

         return [
            'labels'   => $days,
            'current'  => array_map(fn($day) => $currentWeek[$day] ?? 0, array_keys($days)),
            'previous' => array_map(fn($day) => $lastWeek[$day] ?? 0, array_keys($days)),
         ];
      }

      // Customer terbaru beserta 1 transaksi terakhir
      private function getRecentCustomers() {
         return Customer::byOutlet()
                        ->with(['transactions' => fn($query) => $query->latest()->limit(1)])
                        ->orderByDesc('created_at')
                        ->limit(5)
                        ->get();
      }

      // Paket terpopuler berdasarkan jumlah pembelian
      private function getPopularPackages() {
         $query = DB::table('transaction_details')
                    ->join('packages', 'transaction_details.package_id', '=', 'packages.id')
                    ->join('outlets', 'packages.outlet_id', '=', 'outlets.id')
                    ->select(
                       'packages.name as package_name',
                       'outlets.name as outlet_name',
                       DB::raw('SUM(transaction_details.quantity) as total_quantity')
                    )
                    ->where('transaction_details.created_at', '>=', now()->subMonth());
         if(!auth()->user()->can('admin')){
            $query->where('packages.outlet_id', auth()->user()->outlet_id);
         }

         return $query
            ->groupBy('packages.id', 'packages.name', 'outlets.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
      }
   }
