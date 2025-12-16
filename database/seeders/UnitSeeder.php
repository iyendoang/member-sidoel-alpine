<?php

   namespace Database\Seeders;

   use Illuminate\Database\Seeder;
   use App\Models\Unit;

   class UnitSeeder extends Seeder
   {
      public function run(): void {
         $units = [
            'tahun',
            'semester',
            'bulan',
            'siswa',
            'sekolah'
         ];
         foreach($units as $unit) {
            Unit::firstOrCreate([
               'name' => $unit,
            ]);
         }
      }
   }
