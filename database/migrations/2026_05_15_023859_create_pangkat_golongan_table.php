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
        Schema::create('pangkat_golongan', function (Blueprint $table) {
            // Kolom Penghubung (Sesuai Skema Relational.pdf)
            $table->string('id_pengajuan', 10)->primary();
            $table->string('id_pegawai', 10); 
            $table->string('pangkat', 25);     
            $table->string('golongan', 15);   
            $table->date('tmt');
            $table->string('dokumen_sk_cpns', 255)->nullable();
            $table->string('dokumen_sk_pns', 255)->nullable();
            $table->string('dokumen_pak', 255)->nullable();
            $table->string('dokumen_publikasi_ilmiah', 255)->nullable();
            $table->timestamps();
            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan')
                ->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pangkat_golongan');
    }
};