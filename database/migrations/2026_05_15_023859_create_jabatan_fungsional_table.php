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
        // create_jabatan_fungsionals_table
        Schema::create('jabatan_fungsional', function (Blueprint $table) {
            $table->string('id_pengajuan', 10)->primary();
            $table->string('nama_jabatan', 25);
            $table->date('tmt');
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
        Schema::dropIfExists('jabatan_fungsional');
    }
};
