<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration {
      /**
       * Run the migrations.
       */
      public function up(): void {
         Schema::create('transactions', function(Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('outlet_id');
            $table->dateTime('date');
            $table->dateTime('deadline');
            $table->enum('payment_status', ['UNPAID', 'PAID']);
            $table->dateTime('payment_date')->nullable();
            $table->integer('tax_percent')->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->integer('discount_percent')->default(0);
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('additional_fee', 8, 2)->default(0);
            $table->decimal('total_price', 8, 2)->default(0);
            $table->enum('status', ['NEW', 'IN PROGRESS', 'COMPLETED', 'CANCELLED']);
            $table->text('domain')->nullable();
            $table->string('level')->nullable();
            $table->string('district')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
         });
      }

      /**
       * Reverse the migrations.
       */
      public function down(): void {
         Schema::dropIfExists('transactions');
      }
   };
