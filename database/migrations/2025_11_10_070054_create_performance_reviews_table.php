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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->timestamps('tanggal_review')->useCurrent(); // Menggunakan timestamp untuk tanggal & waktu review, default ke waktu sekarang
            $table->decimal('skor', 4, 2); // Skor skala 0-10. Menggunakan (4, 2) cukup untuk angka hingga 10.00
            $table->text('catatan_feedback');
            $table->timestamps();

            // Foreign Key
            $table->foreign('karyawan_id')
              ->references('id')
              ->on('employees')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
