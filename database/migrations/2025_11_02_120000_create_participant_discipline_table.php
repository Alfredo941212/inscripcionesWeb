<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participant_discipline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_profile_id')
                ->constrained('participant_profiles')
                ->cascadeOnDelete();
            $table->foreignId('discipline_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamp('selected_at')->useCurrent();
            $table->timestamps();

            $table->unique(['participant_profile_id', 'discipline_id'], 'participant_discipline_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_discipline');
    }
};
