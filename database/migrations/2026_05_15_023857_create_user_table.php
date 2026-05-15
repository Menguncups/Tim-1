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
        // create_users_table
        Schema::create('user', function (Blueprint $table) {
            $table->string('id_user', 10)->primary();
            $table->string('username', 15);
            $table->string('password', 255);
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
        Schema::dropIfExists('user');
    }
};
