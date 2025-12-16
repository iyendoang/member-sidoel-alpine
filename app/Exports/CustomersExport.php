<?php

   namespace App\Exports;

   use App\Models\Customer;
   use Illuminate\Support\Facades\Auth;
   use Maatwebsite\Excel\Concerns\FromCollection;
   use Maatwebsite\Excel\Concerns\WithHeadings;
   use Maatwebsite\Excel\Concerns\WithStyles;
   use Maatwebsite\Excel\Concerns\WithEvents;
   use Maatwebsite\Excel\Events\AfterSheet;
   use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
   use PhpOffice\PhpSpreadsheet\Style\Border;

   class CustomersExport implements FromCollection, WithHeadings, WithStyles, WithEvents
   {
      protected $outletId;

      public function __construct($outletId = null)
      {
         $this->outletId = $outletId;
      }

      public function collection()
      {
         $query = Customer::with('outlet');

         // non-admin → outlet sendiri
         if (!Auth::user()->can('admin')) {
            $query->where('outlet_id', Auth::user()->outlet_id);
         }
         // admin → pilih outlet
         elseif ($this->outletId) {
            $query->where('outlet_id', $this->outletId);
         }

         $no = 1;

         return $query->get()->map(function ($customer) use (&$no) {
            return [
               'no'          => $no++,
               'name'        => $customer->name,
               'office_name' => $customer->office_name,
               'email'       => $customer->email,
               'phone'       => $customer->phone,
               'address'     => $customer->address,
               'outlet'      => $customer->outlet->name ?? '-',
            ];
         });
      }

      public function headings(): array
      {
         return [
            'No',
            'Name',
            'Office Name',
            'Email',
            'Phone',
            'Address',
            'Outlet',
         ];
      }

      /**
       * Style header
       */
      public function styles(Worksheet $sheet)
      {
         return [
            // Header row
            1 => [
               'font' => ['bold' => true],
            ],
         ];
      }

      /**
       * Event after sheet generated
       */
      public function registerEvents(): array
      {
         return [
            AfterSheet::class => function (AfterSheet $event) {

               $sheet = $event->sheet->getDelegate();

               // ambil posisi terakhir SEBELUM insert
               $lastDataRow = $sheet->getHighestRow();
               $lastColumn  = $sheet->getHighestColumn();

               // =========================
               // INSERT TITLE
               // =========================
               $sheet->insertNewRowBefore(1, 1);

               $sheet->mergeCells("A1:$lastColumn" . "1");
               $sheet->setCellValue('A1', 'CUSTOMERS DATA');

               $sheet->getStyle("A1")->applyFromArray([
                  'font' => [
                     'bold' => true,
                     'size' => 14,
                  ],
                  'alignment' => [
                     'horizontal' => 'center',
                     'vertical'   => 'center',
                  ],
               ]);

               // =========================
               // HEADER STYLE (row 2)
               // =========================
               $sheet->getStyle("A2:$lastColumn" . "2")->applyFromArray([
                  'font' => ['bold' => true],
                  'alignment' => ['horizontal' => 'center'],
               ]);

               // =========================
               // BORDER (HEADER + DATA)
               // =========================
               $sheet->getStyle(
                  "A2:$lastColumn" . ($lastDataRow + 1)
               )->applyFromArray([
                  'borders' => [
                     'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                     ],
                  ],
               ]);

               // =========================
               // AUTO WIDTH
               // =========================
               foreach (range('A', $lastColumn) as $col) {
                  $sheet->getColumnDimension($col)->setAutoSize(true);
               }
            },
         ];
      }
   }
