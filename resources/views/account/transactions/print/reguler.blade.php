<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Invoice #{{ $transaction->invoice }}</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

   <style>
       /* 1. RESET & FONT */
       * {
           font-family: 'Roboto', Arial, Helvetica, sans-serif; /* Font Utama Roboto, Fallback Arial */
           box-sizing: border-box;
       }
       body {
           margin: 0;
           font-size: 12px; /* Font dasar diperkecil sedikit agar lebih compact */
           color: #333;
           line-height: 1.3; /* Line height diperkecil agar rapat */
       }

       /* 2. CONTAINER COMPACT */
       .container {
           background: #fff;
           border-radius: 6px;
           box-shadow: 0 2px 10px rgba(0,0,0,0.05);
           position: relative;
       }

       /* 3. HEADER COMPACT */
       .header {
           text-align: center;
           position: relative;
           z-index: 1;
           border-bottom: 2px solid #f1f5f9;
           padding-bottom: 15px;
       }
       .header h2 {
           margin: 0;
           color: #2563eb;
           font-size: 22px; /* Font size header dikurangi */
           text-transform: uppercase;
           letter-spacing: 0.5px;
       }
       .header p {
           margin: 2px 0; /* Jarak antar baris alamat sangat rapat */
           font-size: 12px;
           color: #64748b;
       }

       /* 4. LAYOUT TABLE (2 KOLOM SEIMBANG) */
       .info-table {
           width: 100%;
           border-collapse: collapse;
           margin-bottom: 20px;
       }
       .info-table td {
           vertical-align: top;
       }
       .col-content {
           width: 48%;
       }
       .col-spacer {
           width: 4%;
       }

       /* 5. INFO GROUPS (RAPAT) */
       .section-title {
           font-size: 10px;
           font-weight: 700;
           color: #94a3b8;
           text-transform: uppercase;
           letter-spacing: 0.5px;
           border-bottom: 1px solid #e2e8f0;
           padding-bottom: 4px;
           margin-bottom: 8px; /* Jarak judul section ke isi lebih rapat */
       }

       .info-group {
           margin-bottom: 6px; /* SANGAT RAPAT (sebelumnya 12px) */
           display: flex;
           align-items: baseline;
       }
       .info-label {
           width: 100px; /* Label fixed width agar lurus */
           font-size: 11px;
           font-weight: 500;
           color: #64748b;
       }
       .info-value {
           flex: 1;
           font-size: 12px;
           font-weight: 700;
           color: #1e293b;
       }

       /* Address special handling */
       .address-value {
           display: block;
           margin-top: 2px;
           font-weight: 500;
           color: #334155;
       }

       /* 6. NOTES BOX (KOLOM KANAN) */
       .admin-notes-box {
           margin-top: 10px;
           background-color: #fffbeb;
           border: 1px solid #fcd34d;
           border-radius: 4px;
           padding: 8px; /* Padding dikurangi */
           font-size: 11px;
           color: #92400e;
           line-height: 1.3;
       }
       .admin-notes-box p {
           margin-top: 0;          /* Hilangkan jarak atas */
           margin-bottom: 4px;     /* Jarak antar paragraf sangat kecil */
           line-height: 1.2;       /* Jarak antar baris kalimat lebih rapat */
       }

       /* Agar paragraf paling akhir tidak menambah jarak kosong di bawah box */
       .admin-notes-box p:last-child {
           margin-bottom: 0;
       }

       /* 7. ITEMS TABLE (COMPACT) */
       .items-table {
           width: 100%;
           border-collapse: collapse;
           font-size: 12px;
       }
       .items-table th {
           background-color: #f8fafc;
           color: #475569;
           padding: 8px; /* Padding header tabel dikurangi */
           text-align: left;
           font-weight: 700;
           text-transform: uppercase;
           font-size: 10px;
           border-top: 1px solid #e2e8f0;
           border-bottom: 1px solid #e2e8f0;
       }
       .items-table td {
           padding: 8px; /* Padding isi tabel dikurangi agar tabel pendek */
           border-bottom: 1px solid #f1f5f9;
           color: #334155;
       }

       /* Totals */
       .total-row td {
           border-top: 1px solid #e2e8f0;
           font-weight: 700;
           color: #1e293b;
       }
       .grand-total td {
           font-size: 13px;
           color: #2563eb;
           border-top: 2px solid #2563eb;
           padding-top: 10px;
       }

       /* Utilities & Badges */
       .text-right { text-align: right; }
       .text-center { text-align: center; }

       .status-badge {
           padding: 2px 8px;
           border-radius: 3px;
           font-size: 10px;
           font-weight: 700;
           text-transform: uppercase;
       }
       .status-paid { background-color: #dcfce7; color: #166534; }
       .status-unpaid { background-color: #fef9c3; color: #854d0e; }

       /* Footer */
       .footer-notes {
           margin-top: 20px;
           background-color: #f8fafc;
           padding: 10px;
           border-radius: 4px;
           font-size: 11px;
           color: #64748b;
           border: 1px dashed #e2e8f0;
       }
       .footer-notes p {
           margin-top: 0;          /* Hilangkan jarak atas */
           margin-bottom: 4px;     /* Jarak antar paragraf sangat kecil */
           line-height: 1.2;       /* Jarak antar baris kalimat lebih rapat */
       }

       /* Agar paragraf paling akhir tidak menambah jarak kosong di bawah box */
       .footer-notes p:last-child {
           margin-bottom: 0;
       }
       .footer {
           font-size: 10px;
           color: #94a3b8;
           text-align: center;
           margin-top: 25px;
       }

       /* Watermark */
       .watermark {
           position: absolute;
           top: 50%; left: 50%;
           transform: translate(-50%, -50%) rotate(-30deg);
           font-size: 50px;
           color: rgba(74, 134, 232, 0.05);
           font-weight: 800;
           z-index: 0;
           pointer-events: none;
       }

       @media print {
           body { background: #fff; }
           .container { box-shadow: none; padding: 0; margin: 0; width: 100%; max-width: 100%; }
       }
   </style>
</head>
<body>

<div class="watermark">Hosting Pro</div>

<div class="container">
   <div class="header">
      <h2>{{ strtoupper($transaction->outlet->name) }}</h2>
      <p>{{ strtoupper($transaction->outlet->address) }}</p>
      <p>Phone: {{ strtoupper($transaction->outlet->phone) }}</p>
   </div>

   <table class="info-table">
      <tr>
         <td class="col-content">
            <div class="section-title">TRANSACTION DETAILS</div>

            <div class="info-group">
               <span class="info-label">Invoice No.</span>
               <span class="info-value">#{{ $transaction->invoice }}</span>
            </div>

            <div class="info-group">
               <span class="info-label">Issued Date</span>
               <span class="info-value">{{ date('d M Y', strtotime($transaction->created_at)) }}</span>
            </div>

            <div class="info-group">
               <span class="info-label">Expired Date</span>
               <span class="info-value" style="color: #ef4444;">
                  {{ date('d M Y', strtotime($transaction->deadline)) }}
               </span>
            </div>

            <div class="info-group">
               <span class="info-label">Status</span>
               <span class="status-badge status-{{ strtolower($transaction->payment_status) }}">
                    {{ strtoupper($transaction->payment_status) }}
               </span>
            </div>
         </td>

         <td class="col-spacer"></td>

         <td class="col-content">
            <div class="section-title">BILL TO</div>

            <div class="info-group">
               <span class="info-label">Name</span>
               <span class="info-value">{{ $transaction->customer->name }}</span>
            </div>

            <div class="info-group">
               <span class="info-label">Phone</span>
               <span class="info-value">{{ $transaction->customer->phone }}</span>
            </div>

            <div class="info-group" style="align-items: flex-start;">
               <span class="info-label">Address</span>
               <span class="info-value address-value">
                   {{ $transaction->customer->address }}
               </span>
            </div>

            @if($transaction->outlet->notes)
               <div class="admin-notes-box">
                  {!! $transaction->outlet->notes !!}
               </div>
            @endif
         </td>
      </tr>
   </table>

   <table class="items-table">
      <thead>
      <tr>
         <th width="5%">#</th>
         <th width="45%">Service</th>
         <th width="15%" class="text-left">Qty</th>
         <th width="15%" class="text-left">Unit</th>
         <th width="15%" class="text-right">Price</th>
         <th width="20%" class="text-right">Total</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($transaction->transaction_details as $item)
         <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $item->package->name }}</strong></td>
            <td class="text-left">{{ $item->quantity }}</td>
            <td class="text-left">{{ $item->unit }}</td>
            <td class="text-right">Rp {{ number_format($item->package->price, 0, ',', '.') }}</td>
            <td class="text-right" style="font-weight:600;">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
         </tr>
      @endforeach

      <tr class="total-row">
         <td colspan="5" class="text-right">Subtotal</td>
         <td class="text-right">Rp {{ number_format($transaction->transaction_details->sum('total'), 0, ',', '.') }}</td>
      </tr>

      @if($transaction->discount_percent > 0)
         <tr>
            <td colspan="5" class="text-right" style="color: #ef4444;">Discount ({{ $transaction->discount_percent }}%)</td>
            <td class="text-right" style="color: #ef4444;">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
         </tr>
      @endif

      @if($transaction->tax_percent > 0)
         <tr>
            <td colspan="5" class="text-right">Tax ({{ $transaction->tax_percent }}%)</td>
            <td class="text-right">+ Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</td>
         </tr>
      @endif

      @if($transaction->additional_fee > 0)
         <tr>
            <td colspan="5" class="text-right">Additional Fee</td>
            <td class="text-right">+ Rp {{ number_format($transaction->additional_fee, 0, ',', '.') }}</td>
         </tr>
      @endif

      <tr class="grand-total">
         <td colspan="5" class="text-right">GRAND TOTAL</td>
         <td class="text-right">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
      </tr>
      </tbody>
   </table>

   @if($transaction->notes)
      <div class="footer-notes">
         <strong>Information:</strong> {!! $transaction->notes !!}
      </div>
   @endif

   <div class="footer">
      Generated on {{ date('d/m/Y H:i') }}
   </div>
</div>

</body>
</html>