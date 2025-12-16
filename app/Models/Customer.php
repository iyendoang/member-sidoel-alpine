<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class Customer extends Model
   {
      /**
       * fillable
       *
       * @var array
       */
      protected $fillable = [
         'outlet_id',
         'name',
         'office_name',
         'email',
         'phone',
         'address',
      ];

      /**
       * outlet
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function outlet() {
         return $this->belongsTo(Outlet::class);
      }

      /**
       * transactions
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function transactions() {
         return $this->hasMany(Transaction::class);
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
