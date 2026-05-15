Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');

    $table->string('pegawai_id_pegawai', 10)->nullable();

    $table->foreign('pegawai_id_pegawai')
        ->references('id_pegawai')
        ->on('pegawai')
        ->nullOnDelete();

    $table->rememberToken();
    $table->timestamps();
});