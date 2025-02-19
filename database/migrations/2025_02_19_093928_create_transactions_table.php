<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->enum('type', ['deposit', 'transfer', 'reversal']);
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'completed', 'reversed'])->default('pending');
            $table->string('transaction_code')->unique();
            $table->foreignId('reversed_transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }

};

