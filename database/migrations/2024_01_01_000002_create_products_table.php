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
            $table->id();
            $table->string('nama_produk', 150);
            $table->string('kategori', 50)->default('Kaos');
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('stok')->default(10);
            $table->string('ukuran', 20)->default('All Size');
            $table->text('deskripsi')->nullable();
            $table->text('gambar')->nullable();
            $table->timestamps();
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
