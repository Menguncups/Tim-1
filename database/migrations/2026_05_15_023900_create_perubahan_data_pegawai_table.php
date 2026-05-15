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
        // create_perubahan_data_pegawais_table
        Schema::create('perubahan_data_pegawai', function (Blueprint $table) {
            $table->string('id_pengajuan', 10);
            $table->string('kolom_diubah', 15);
            $table->string('nilai_lama', 15);
            $table->string('nilai_baru', 15);
            $table->timestamps();

            $table->primary(['id_pengajuan', 'kolom_diubah']);

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
        Schema::dropIfExists('perubahan_data_pegawai');
    }
};
