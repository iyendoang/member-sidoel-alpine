<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration {
      /**
       * Run the migrations.
       */
      public function up(): void {
         Schema::create('transaction_details', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('package_id');
            $table->integer('quantity');
            $table->string('unit');
            $table->decimal('total', 8, 2);
            $table->timestamps();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
         });
      }

      /**
       * Reverse the migrations.
       */
      public function down(): void {
         Schema::dropIfExists('transaction_details');
      }
   };
