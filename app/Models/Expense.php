<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class Expense extends Model
   {
      /**
       * fillable
       *
       * @var array
       */
      protected $fillable = [
         'outlet_id',
         'category_expense_id',
         'amount',
         'description',
         'expense_date',
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
       * category expense
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function category_expense() {
         return $this->belongsTo(CategoryExpense::class);
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
