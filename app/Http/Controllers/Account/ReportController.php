<?php

   namespace App\Http\Controllers\Account;

   use Carbon\Carbon;
   use App\Models\Outlet;
   use App\Models\Expense;
   use App\Models\Transaction;
   use Illuminate\Http\Request;
   use App\Exports\ReportExport;
   use Illuminate\Support\Facades\DB;
   use App\Http\Controllers\Controller;
   use Maatwebsite\Excel\Facades\Excel;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class ReportController extends Controller implements HasMiddleware
   {
      /**
       * Define middleware for this controller.
       */
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:reports.cashflow'], only: ['index']),
         ];
      }

      /**
       * index
       *
       * @param  mixed $request
       * @return void
       */
      public function index(Request $request)
      {
         // Ambil semua outlet untuk dropdown
         $outlets = Outlet::all();

         // Jika belum ada filter, langsung tampilkan view awal
         if (!$request->filled(['start_date', 'end_date'])) {
            return view('account.reports.index', compact('outlets'));
         }

         // Validasi hanya dilakukan jika ada filter
         $request->validate([
            'outlet_id' => 'nullable|exists:outlets,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
         ]);

         // Ambil data dari request
         $outletId = $request->outlet_id;
         $startDate = Carbon::parse($request->start_date)->startOfDay();
         $endDate = Carbon::parse($request->end_date)->endOfDay();

         // Revenue
         $revenueData = Transaction::query()
                                   ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
                                   ->where('payment_status', 'PAID')
                                   ->whereBetween('created_at', [$startDate, $endDate])
                                   ->select(
                                      DB::raw('DATE(created_at) as date'),
                                      DB::raw('SUM(total_price) as total')
                                   )
                                   ->groupBy('date')
                                   ->orderBy('date')
                                   ->get();

         // Expenses
         $expensesData = Expense::query()
                                ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
                                ->whereBetween('expense_date', [$startDate, $endDate])
                                ->select(
                                   DB::raw('DATE(expense_date) as date'),
                                   DB::raw('SUM(amount) as total')
                                )
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get();

         // Summary
         $totalRevenue   = $revenueData->sum('total');
         $totalExpenses  = $expensesData->sum('total');
         $profit          = $totalRevenue - $totalExpenses;
         $selectedOutlet = $outletId ? Outlet::find($outletId) : null;

         return view('account.reports.index', compact(
            'outlets',
            'selectedOutlet',
            'startDate',
            'endDate',
            'revenueData',
            'expensesData',
            'totalRevenue',
            'totalExpenses',
            'profit'
         ));
      }

      /**
       * export
       *
       * @param  mixed $request
       * @return void
       */
      public function export(Request $request)
      {
         // Validasi input
         $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'outlet_id' => 'nullable|exists:outlets,id',
         ]);

         // Ambil data dari request
         $outletId = $request->outlet_id;
         $startDate = Carbon::parse($request->start_date)->startOfDay();
         $endDate = Carbon::parse($request->end_date)->endOfDay();

         // Ambil data dari database
         $revenueRaw = Transaction::query()
                                  ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
                                  ->where('payment_status', 'PAID')
                                  ->whereBetween('created_at', [$startDate, $endDate])
                                  ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
                                  ->groupBy('date')
                                  ->get()
                                  ->keyBy('date');

         $expensesRaw = Expense::query()
                               ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
                               ->whereBetween('expense_date', [$startDate, $endDate])
                               ->select(DB::raw('DATE(expense_date) as date'), DB::raw('SUM(amount) as total'))
                               ->groupBy('date')
                               ->get()
                               ->keyBy('date');

         // Normalisasi agar setiap tanggal tetap muncul
         $revenueData = collect();
         $expensesData = collect();
         $totalRevenue = 0;
         $totalExpenses = 0;

         $date = $startDate->copy();
         while ($date->lte($endDate)) {
            $dateStr = $date->format('Y-m-d');

            $revenueTotal = $revenueRaw[$dateStr]->total ?? 0;
            $expenseTotal = $expensesRaw[$dateStr]->total ?? 0;

            $revenueData->push((object)[
               'date' => $dateStr,
               'total' => $revenueTotal,
            ]);

            $expensesData->push((object)[
               'date' => $dateStr,
               'total' => $expenseTotal,
            ]);

            $totalRevenue += $revenueTotal;
            $totalExpenses += $expenseTotal;

            $date->addDay();
         }

         $profit = $totalRevenue - $totalExpenses;
         $selectedOutlet = $outletId ? Outlet::find($outletId) : null;

         $filename = 'laporan_keuangan_' . now()->format('Ymd_His') . '.xlsx';

         return Excel::download(
            new ReportExport($revenueData, $expensesData, $totalRevenue, $totalExpenses, $profit, $startDate, $endDate, $selectedOutlet),
            $filename
         );
      }

   }
