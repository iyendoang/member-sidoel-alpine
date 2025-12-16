<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class Transaction extends Model
   {
      /**
       * fillable
       *
       * @var array
       */
      protected $fillable = [
         'invoice',
         'customer_id',
         'user_id',
         'outlet_id',
         'date',
         'deadline',
         'status',
         'payment_status',
         'payment_date',
         'tax_percent',
         'tax_amount',
         'discount_percent',
         'discount_amount',
         'additional_fee',
         'total_price',
         'domain',
         'level',
         'district',
         'notes',
      ];

      /**
       * transaction_details
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function transaction_details() {
         return $this->hasMany(TransactionDetail::class);
      }

      /**
       * customer
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function customer() {
         return $this->belongsTo(Customer::class);
      }

      /**
       * user
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function user() {
         return $this->belongsTo(User::class);
      }

      /**
       * outlet
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function outlet() {
         return $this->belongsTo(Outlet::class);
      }

      /**
       * scopeByOutlet
       *
       * @param mixed $query
       *
       * @return void
       */
      public function scopeByOutlet($query) {
         if(!auth()->user()->can('admin')){
            $query->where('outlet_id', auth()->user()->outlet_id);
         }

         return $query;
      }
   }
