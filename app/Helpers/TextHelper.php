<?php

   namespace App\Helpers;

   use Illuminate\Support\Str;

   class StringHelper
   {
      public static function slugLower(string $value): string {
         return Str::slug(strtolower(trim($value)));
      }
   }
