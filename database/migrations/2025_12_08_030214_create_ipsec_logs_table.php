<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipsec_logs', function (Blueprint $table) {
            $table->id();
            $table->string('mode');            // tunnel / transport
            $table->string('encryption');      // AES-256
            $table->string('auth');            // SHA-256
            $table->string('ip_origen')->nullable();
            $table->string('ip_destino')->nullable();
            $table->string('resultado');       // estado del túnel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipsec_logs');
    }
};
