<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration {
      /**
       * Run the migrations.
       */
      public function up(): void {
         Schema::create('packages', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('category_package_id');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->timestamps();
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('category_package_id')->references('id')->on('category_packages')->onDelete('cascade');
         });
      }

      /**
       * Reverse the migrations.
       */
      public function down(): void {
         Schema::dropIfExists('packages');
      }
   };
