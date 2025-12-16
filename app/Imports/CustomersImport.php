<?php

   namespace App\Imports;

   use App\Models\Customer;
   use Illuminate\Contracts\Queue\ShouldQueue;
   use Illuminate\Support\Facades\Log;
   use Maatwebsite\Excel\Concerns\{
      ToModel,
      WithHeadingRow,
      WithValidation,
      WithChunkReading,
      WithBatchInserts,
      SkipsOnFailure,
      SkipsFailures,
      WithHeadingRowFormatter
   };

   class CustomersImport implements
      ToModel,
      WithHeadingRow,
      WithValidation,
      WithChunkReading,
      WithBatchInserts,
      ShouldQueue,
      SkipsOnFailure
   {
      use SkipsFailures;

      public int $successCount = 0; // Set default 0
      protected int $targetOutletId;

      public function __construct(int $userOutletId, bool $isAdmin, ?int $selectedOutletId = null)
      {
         $this->targetOutletId = ($isAdmin && $selectedOutletId)
            ? $selectedOutletId
            : $userOutletId;
      }

      public function headingRow(): int
      {
         return 2;
      }

      public static function headingRowFormatter(): string
      {
         return 'slug';
      }

      public function chunkSize(): int
      {
         return 500;
      }

      public function batchSize(): int
      {
         return 500;
      }

      public function model(array $row)
      {
         if (!isset($row['email']) || !isset($row['name'])) {
            return null;
         }

         // --- TAMBAHAN PENTING: Increment counter ---
         $this->successCount++;
         // -------------------------------------------

         return Customer::updateOrCreate(
            [
               'email'     => $row['email'],
               'outlet_id' => $this->targetOutletId
            ],
            [
               'name'        => $row['name'],
               'office_name' => $row['office_name'] ?? null,
               'phone'       => $row['phone'] ?? null,
               'address'     => $row['address'] ?? null,
            ]
         );
      }

      public function rules(): array
      {
         return [
            '*.name'        => ['required', 'string'],
            '*.email'       => ['required', 'email'],
            '*.office_name' => ['nullable', 'string'],
            '*.phone'       => ['nullable'],
            '*.address'     => ['nullable'],
         ];
      }

      public function customValidationMessages()
      {
         return [
            '*.email.required' => 'Kolom Email wajib diisi.',
            '*.email.email'    => 'Format Email tidak valid.',
         ];
      }

      public function onFailure(...$failures)
      {
         // Biarkan kosong atau log saja, karena kita akan menangkapnya di Controller
      }
   }