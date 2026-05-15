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
        // create_pengajuan_surat_tugas_table
        Schema::create('pengajuan_surat_tugas', function (Blueprint $table) {
            $table->string('id_pengajuan', 10)->primary();
            $table->string('nama_pengusul', 50);
            $table->date('waktu_pelaksana');
            $table->integer('lama_pelaksanaan');
            $table->string('perihal', 50);
            $table->string('berkas_pendukung', 100);
            $table->timestamps();

            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat_tugas');
    }
};
