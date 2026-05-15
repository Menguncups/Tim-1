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
        // create_pengajuans_table
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->string('id_pengajuan', 10)->primary();
            $table->date('tanggal_pengajuan');
            $table->string('jenis_pengajuan', 25);
            $table->string('status', 20);
            $table->string('pegawai_id_pegawai', 10);
            $table->timestamps();

            $table->foreign('pegawai_id_pegawai')
                ->references('id_pegawai')
                ->on('pegawai')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
