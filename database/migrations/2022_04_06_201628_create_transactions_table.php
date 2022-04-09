<?php

use App\Services\TransactionService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->uuid('uuid')->unique();
            $table->uuid('external_id')->unique();
            $table->foreignId('account_from_id')->constrained('accounts');
            $table->string('pix_key_kind');
            $table->string('pix_key_key');
            $table->unsignedDouble('amount');
            $table->enum('moviment', ['debit', 'credit']);
            $table->string('status')->default(TransactionService::TRANSACTION_PENDING);
            $table->string('description')->nullable();
            $table->string('cancel_description')->nullable();
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
};
