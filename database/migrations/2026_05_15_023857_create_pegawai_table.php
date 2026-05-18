<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->string('id_pegawai', 10)->primary();

            $table->string('nip', 18)->unique();
            $table->string('nidn', 10)->nullable()->unique();

            $table->string('nama', 50);
            $table->string('jenis_kelamin', 10);
            $table->date('tanggal_lahir');

            $table->string('no_hp', 14);
            $table->string('no_hp_darurat', 14)->nullable();

            $table->string('homebase', 80);
            $table->string('email', 50)->unique();

            $table->string('pangkat_golongan', 50);
            $table->string('jabatan_fungsional', 50);

            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};