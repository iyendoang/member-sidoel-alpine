<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class Outlet extends Model
   {
      /**
       * fillable
       *
       * @var array
       */
      protected $fillable = [
         'code_outlet',
         'name',
         'address',
         'phone',
         'notes',
      ];

      /**
       * users
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function users() {
         return $this->hasMany(User::class);
      }

      /**
       * customers
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function customers() {
         return $this->hasMany(Customer::class);
      }

      /**
       * packages
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function packages() {
         return $this->hasMany(Package::class);
      }

      /**
       * expenses
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function expenses() {
         return $this->hasMany(Expense::class);
      }

      /**
       * transactions
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function transactions() {
         return $this->hasMany(Transaction::class);
      }
   }
