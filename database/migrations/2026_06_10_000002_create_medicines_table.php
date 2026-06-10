<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori');
            $table->string('satuan');
            $table->decimal('harga', 10, 2);
            $table->integer('stok');
            $table->integer('min_stock');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
