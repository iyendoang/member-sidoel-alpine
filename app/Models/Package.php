<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class Package extends Model
   {
      /**
       * fillable
       *
       * @var array
       */
      protected $fillable = [
         'outlet_id',
         'category_package_id',
         'type',
         'name',
         'price',
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
       * category package
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function category_package() {
         return $this->belongsTo(CategoryPackage::class);
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
