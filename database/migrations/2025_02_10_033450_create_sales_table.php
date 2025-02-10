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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('invoice_number', 50)->unique(); // VARCHAR(50) UNIQUE
            $table->decimal('total_amount', 10, 2); // DECIMAL(10,2) NOT NULL
            $table->string('payment_method', 50)->nullable(); // VARCHAR(50)
            $table->string('payment_status', 50)->nullable(); // VARCHAR(50)
            $table->string('customer_name')->nullable(); // VARCHAR(255)
            $table->timestamps(); // created_at and updated_at TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
