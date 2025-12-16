<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <title>Invoice - {{ $transaction->invoice }}</title>
   <style>
       * {
           box-sizing: border-box;
           font-family: monospace;
       }

       body {
           font-size: 10px;
           line-height: 1.4;
           margin: 0;
           padding: 0;
           background: #fff;
       }

       .print-area {
           width: 58mm;
           padding: 10px;
           margin: auto;
       }

       .center {
           text-align: center;
       }

       .bold {
           font-weight: bold;
       }

       .divider {
           border-top: 1px dashed #000;
           margin: 5px 0;
       }

       table {
           width: 100%;
           border-collapse: collapse;
           font-size: 10px;
       }

       td {
           padding: 2px 0;
       }

       .text-right {
           text-align: right;
       }

       .text-left {
           text-align: left;
       }

       .text-center {
           text-align: center;
       }

       .footer {
           text-align: center;
           margin-top: 10px;
       }

       @media print {
           @page {
               margin: 0;
           }

           body {
               padding: 0;
               margin: 0;
           }

           .print-area {
               padding: 0;
           }
       }
   </style>
   <script>
       window.onload = function() {
           window.print();
           // Optional: close window automatically after printing
           setTimeout(() => window.close(), 500);
       };
   </script>
</head>
<body>
<div class="print-area">
   <div class="center bold">
      {{ strtoupper($transaction->outlet->name) }}<br>
   </div>
   <div class="center">
      {{ strtoupper($transaction->outlet->address) }}<br>
      Phone: {{ strtoupper($transaction->outlet->phone) }}
   </div>

   <div class="divider"></div>

   <table>
      <tr>
         <td>Invoice #</td>
         <td class="text-right">{{ $transaction->invoice }}</td>
      </tr>
      <tr>
         <td>Date</td>
         <td class="text-right">{{ date('d/m/Y H:i', strtotime($transaction->created_at)) }}</td>
      </tr>
      <tr>
         <td>Status</td>
         <td class="text-right">{{ strtoupper($transaction->payment_status) }}</td>
      </tr>
   </table>

   <div class="divider"></div>

   <table>
      <tr>
         <td colspan="2" class="bold">CUSTOMER</td>
      </tr>
      <tr>
         <td>Name</td>
         <td class="text-right">{{ $transaction->customer->name }}</td>
      </tr>
      <tr>
         <td>Office Name</td>
         <td class="text-right">{{ $transaction->customer->office_name }}</td>
      </tr>
      <tr>
         <td>Phone</td>
         <td class="text-right">{{ $transaction->customer->phone }}</td>
      </tr>
   </table>

   <div class="divider"></div>

   <table>
      <thead>
      <tr>
         <td class="bold">Item</td>
         <td class="bold text-right">Amount</td>
      </tr>
      </thead>
      <tbody>
      @foreach ($transaction->transaction_details as $item)
         <tr>
            <td>
               {{ $item->package->name }}<br>
               {{ $item->quantity }} {{ $item->package->unit }} x Rp{{ number_format($item->package->price, 0, ',', '.') }}
            </td>
            <td class="text-right">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
         </tr>
      @endforeach
      </tbody>
   </table>

   <div class="divider"></div>

   <table>
      <tr>
         <td class="bold">Subtotal</td>
         <td class="text-right">Rp{{ number_format($transaction->transaction_details->sum('total'), 0, ',', '.') }}</td>
      </tr>

      @if($transaction->discount_percent > 0)
         <tr>
            <td>Discount ({{ $transaction->discount_percent }}%)</td>
            <td class="text-right">- Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
         </tr>
      @endif

      @if($transaction->tax_percent > 0)
         <tr>
            <td>Tax ({{ $transaction->tax_percent }}%)</td>
            <td class="text-right">+ Rp{{ number_format($transaction->tax_amount, 0, ',', '.') }}</td>
         </tr>
      @endif

      @if($transaction->additional_fee > 0)
         <tr>
            <td>Additional Fee</td>
            <td class="text-right">+ Rp{{ number_format($transaction->additional_fee, 0, ',', '.') }}</td>
         </tr>
      @endif

      <tr class="bold">
         <td>TOTAL</td>
         <td class="text-right">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
      </tr>
   </table>

   <div class="divider"></div>

   <table>
      <tr>
         <td>Expired Date:</td>
         <td class="text-right">{{ date('d/m/Y H:i', strtotime($transaction->deadline)) }}</td>
      </tr>
   </table>

   <div class="divider"></div>

   <div class="footer">
      @if($transaction->notes)
         {!! $transaction->notes !!}<br>
      @endif
      Hosting Pro services<br>
   </div>
</div>
</body>
</html>
