<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;
   use Illuminate\Support\Str;

   class Unit extends Model
   {
      use HasFactory;

      protected $fillable = [
         'name',
      ];
      public function setNameAttribute($value): void {
         $this->attributes['name'] = Str::slug(strtolower(trim($value)));
      }
   }
