<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jabatan_fungsional', function (Blueprint $table) {
            $table->string('dokumen_sk_cpns', 100)->nullable()->after('berkas_pendukung');
            $table->string('dokumen_sk_pns', 100)->nullable()->after('dokumen_sk_cpns');
            $table->string('dokumen_pak', 100)->nullable()->after('dokumen_sk_pns');
            $table->string('dokumen_publikasi_ilmiah', 100)->nullable()->after('dokumen_pak');
        });

        Schema::table('pangkat_golongan', function (Blueprint $table) {
            $table->string('dokumen_sk_cpns', 100)->nullable()->after('berkas_pendukung');
            $table->string('dokumen_sk_pns', 100)->nullable()->after('dokumen_sk_cpns');
            $table->string('dokumen_pak', 100)->nullable()->after('dokumen_sk_pns');
            $table->string('dokumen_publikasi_ilmiah', 100)->nullable()->after('dokumen_pak');
        });
    }

    public function down(): void
    {
        Schema::table('jabatan_fungsional', function (Blueprint $table) {
            $table->dropColumn([
                'dokumen_sk_cpns',
                'dokumen_sk_pns',
                'dokumen_pak',
                'dokumen_publikasi_ilmiah',
            ]);
        });

        Schema::table('pangkat_golongan', function (Blueprint $table) {
            $table->dropColumn([
                'dokumen_sk_cpns',
                'dokumen_sk_pns',
                'dokumen_pak',
                'dokumen_publikasi_ilmiah',
            ]);
        });
    }
};