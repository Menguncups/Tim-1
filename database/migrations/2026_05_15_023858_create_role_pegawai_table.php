<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_pegawai', function (Blueprint $table) {
            $table->string('role_id_role', 5);
            $table->string('pegawai_id_pegawai', 10);
            $table->timestamps();

            $table->primary(['role_id_role', 'pegawai_id_pegawai']);

            $table->foreign('role_id_role')
                ->references('id_role')
                ->on('role')
                ->cascadeOnDelete();

            $table->foreign('pegawai_id_pegawai')
                ->references('id_pegawai')
                ->on('pegawai')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_pegawai');
    }
};