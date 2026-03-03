<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('billing_date');
            $table->decimal('usage_units', 10, 2);
            $table->decimal('base_charge', 10, 2);
            $table->decimal('usage_charge', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['Paid', 'Pending'])->default('Pending');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
