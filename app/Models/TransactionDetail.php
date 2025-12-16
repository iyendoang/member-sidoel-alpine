<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class TransactionDetail extends Model
   {
      /**
       * fillable
       *
       * @var array
       */
      protected $fillable = [
         'transaction_id',
         'package_id',
         'quantity',
         'unit',
         'total',
      ];

      /**
       * transaction
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function transaction() {
         return $this->belongsTo(Transaction::class);
      }

      /**
       * package
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function package() {
         return $this->belongsTo(Package::class);
      }
   }
