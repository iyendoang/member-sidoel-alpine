<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration {
      /**
       * Run the migrations.
       */
      public function up(): void {
         Schema::create('expenses', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('category_expense_id');
            $table->decimal('amount', 8, 2);
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->timestamps();
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('category_expense_id')->references('id')->on('category_expenses')->onDelete('cascade');
         });
      }

      /**
       * Reverse the migrations.
       */
      public function down(): void {
         Schema::dropIfExists('expenses');
      }
   };
