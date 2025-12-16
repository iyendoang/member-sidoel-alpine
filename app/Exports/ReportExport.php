<?php

   namespace App\Exports;

   use Illuminate\Contracts\View\View;
   use Maatwebsite\Excel\Concerns\FromView;

   class ReportExport implements FromView
   {
      // properties to hold the data for the report
      protected $revenueData;
      protected $expensesData;
      protected $totalRevenue;
      protected $totalExpenses;
      protected $profit;
      protected $startDate;
      protected $endDate;
      protected $selectedOutlet;

      // Constructor to initialize the properties with data
      public function __construct($revenueData, $expensesData, $totalRevenue, $totalExpenses, $profit, $startDate, $endDate, $selectedOutlet) {
         $this->revenueData    = $revenueData;
         $this->expensesData   = $expensesData;
         $this->totalRevenue   = $totalRevenue;
         $this->totalExpenses  = $totalExpenses;
         $this->profit         = $profit;
         $this->startDate      = $startDate;
         $this->endDate        = $endDate;
         $this->selectedOutlet = $selectedOutlet;
      }

      // Method to return the view for the export
      public function view(): View {
         return view('account.reports.export', [
            'revenueData'    => $this->revenueData,
            'expensesData'   => $this->expensesData,
            'totalRevenue'   => $this->totalRevenue,
            'totalExpenses'  => $this->totalExpenses,
            'profit'         => $this->profit,
            'startDate'      => $this->startDate,
            'endDate'        => $this->endDate,
            'selectedOutlet' => $this->selectedOutlet,
         ]);
      }
   }
