<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('transaction_code')->unique();
            $table->string('customer_name');
            $table->string('uri_')->nullable();
            $table->decimal('amount', 8, 2);
            
            $table->unsignedBigInteger('cashier');
            $table->foreign('cashier')->references('id')->on('users')->onDelete('cascade');

            $table->enum('payment_method', ['bank_transfer', 'credit_card', 'cash','e_wallet'])->default('cash'); 
            $table->enum('status', ['pending', 'completed', 'canceled','refund'])->default('pending'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
