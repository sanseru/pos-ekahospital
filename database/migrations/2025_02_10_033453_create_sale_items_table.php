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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('sale_id')->constrained(); // FOREIGN KEY to sales table
            $table->foreignId('product_id')->constrained(); // FOREIGN KEY to products table
            $table->integer('quantity'); // INT NOT NULL
            $table->decimal('price', 10, 2); // DECIMAL(10,2) NOT NULL
            $table->decimal('subtotal', 10, 2); // DECIMAL(10,2) NOT NULL
            $table->timestamps(); // created_at and updated_at TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
