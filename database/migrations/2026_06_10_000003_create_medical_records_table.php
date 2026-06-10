<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->text('keluhan');
            $table->string('kode_icd_10');
            $table->text('tindakan_medis');
            $table->string('tekanan_darah');
            $table->string('suhu');
            $table->string('nadi');
            $table->string('berat_badan');
            $table->string('tinggi_badan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
