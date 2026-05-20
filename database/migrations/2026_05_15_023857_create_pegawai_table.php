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
            $table->string('nidn', 10)->unique()->nullable(); 
            $table->string('nama', 50);                  
            $table->string('jenis_kelamin', 15);         
            $table->date('tgl_lahir');                   
            $table->string('tempat_lahir', 50)->nullable(); 
            $table->string('homebase', 50);              
            $table->string('no_hp', 15);                 
            $table->string('no_hp_darurat', 15);         
            $table->string('email', 50)->unique();       
            $table->string('foto', 255)->nullable();     
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};