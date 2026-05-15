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
        // create_pangkat_golongans_table
        Schema::create('pangkat_golongan', function (Blueprint $table) {
            $table->string('id_pengajuan', 10)->primary();
            $table->string('pangkat', 25);
            $table->string('golongan', 5);
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
        Schema::dropIfExists('pangkat_golongan');
    }
};
