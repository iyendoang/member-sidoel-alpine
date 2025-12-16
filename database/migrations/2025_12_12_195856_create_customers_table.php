<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration {
      /**
       * Run the migrations.
       */
      public function up(): void {
         Schema::create('customers', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->string('name');
            $table->string('office_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->timestamps();
            // Add foreign key constraint
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
         });
      }

      /**
       * Reverse the migrations.
       */
      public function down(): void {
         Schema::dropIfExists('customers');
      }
   };
