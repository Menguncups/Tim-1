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
        // create_pegawai_table
        Schema::create('pegawai', function (Blueprint $table) {
            $table->string('id_pegawai', 10)->primary();
            $table->unsignedBigInteger('nip');
            $table->unsignedBigInteger('nidn');
            $table->string('nama', 50);
            $table->string('jenis_kelamin', 10);
            $table->date('tanggal_lahir');
            $table->string('no_hp', 14);
            $table->string('no_hp_darurat', 14)->nullable();
            $table->string('homebase', 50);
            $table->string('email', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
