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
        // create_verifikasis_table
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->string('id_verifikasi', 10)->primary();
            $table->date('tanggal_verifikasi');
            $table->string('tahap_verifikasi', 20);
            $table->string('catatan', 250)->nullable();
            $table->string('user_id_user', 10);
            $table->string('pengajuan_id_pengajuan', 10);
            $table->timestamps();

            $table->foreign('user_id_user')
                ->references('id_user')
                ->on('user')
                ->cascadeOnDelete();

            $table->foreign('pengajuan_id_pengajuan')
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
        Schema::dropIfExists('verifikasi');
    }
};
