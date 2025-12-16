<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class CategoryExpense extends Model
   {
      /**
       * fillable
       *
       * @var array
       */
      protected $fillable = [
         'name',
         'description',
      ];

      /**
       * expenses
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function expenses() {
         return $this->hasMany(Expense::class);
      }
   }

