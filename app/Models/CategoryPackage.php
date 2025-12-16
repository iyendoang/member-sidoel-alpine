<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class CategoryPackage extends Model
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
       * packages
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function packages()
      {
         return $this->hasMany(Package::class);
      }
   }
