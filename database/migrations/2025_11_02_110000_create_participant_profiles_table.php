<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('birthdate')->nullable();
            $table->string('curp', 18)->nullable();
            $table->unsignedInteger('seniority_years')->default(0);
            $table->string('constancia_path')->nullable();
            $table->string('cfdi_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status')->default('pending'); // pending | accepted | rejected
            $table->text('status_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_profiles');
    }
};
