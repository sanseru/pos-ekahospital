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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // This creates BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('name'); // VARCHAR(255) NOT NULL
            $table->text('description')->nullable(); // TEXT that can be NULL
            $table->decimal('price', 10, 2); // DECIMAL(10,2) NOT NULL
            $table->integer('stock')->default(0); // INT NOT NULL DEFAULT 0
            $table->string('category', 100)->nullable(); // VARCHAR(100)
            $table->string('type')->default('Barang'); // Barang atau Jasa
            $table->timestamps(); // Creates created_at and updated_at TIMESTAMP columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
