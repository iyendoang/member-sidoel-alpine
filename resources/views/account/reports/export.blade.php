<table>
   <thead>
   <tr>
      <th colspan="4" style="font-weight: bold; font-size: 16px;">Laporan Keuangan</th>
   </tr>
   <tr>
      <th colspan="4">
         Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
         s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
      </th>
   </tr>
   <tr>
      <th colspan="4">
         Outlet: {{ $selectedOutlet ? $selectedOutlet->name : 'Semua Outlet' }}
      </th>
   </tr>
   <tr></tr> <!-- Spacer -->
   <tr style="font-weight: bold;">
      <th>Tanggal</th>
      <th>Pemasukan (Revenue)</th>
      <th>Pengeluaran (Expense)</th>
      <th>Profit</th>
   </tr>
   </thead>
   <tbody>
   @php
      $currentDate = \Carbon\Carbon::parse($startDate);
      $end = \Carbon\Carbon::parse($endDate);
   @endphp

   @while($currentDate <= $end)
      @php
         $dateStr = $currentDate->format('Y-m-d');
         $revenue = $revenueData->firstWhere('date', $dateStr);
         $expense = $expensesData->firstWhere('date', $dateStr);
         $profitRow = ($revenue->total ?? 0) - ($expense->total ?? 0);
      @endphp
      <tr>
         <td>{{ $currentDate->format('d-m-Y') }}</td>
         <td>Rp {{ number_format($revenue->total ?? 0, 0, ',', '.') }}</td>
         <td>Rp {{ number_format($expense->total ?? 0, 0, ',', '.') }}</td>
         <td>Rp {{ number_format($profitRow, 0, ',', '.') }}</td>
      </tr>
      @php
         $currentDate->addDay();
      @endphp
   @endwhile

   <tr></tr> <!-- Spacer -->

   <tr style="font-weight: bold;">
      <td>Total</td>
      <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
      <td>Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
      <td>Rp {{ number_format($profit, 0, ',', '.') }}</td>
   </tr>
   </tbody>
</table>
