<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participant_discipline', function (Blueprint $table) {
            if (!Schema::hasColumn('participant_discipline', 'status')) {
                $table->string('status', 20)->default('pending')->after('selected_at');
            }
            if (!Schema::hasColumn('participant_discipline', 'status_notes')) {
                $table->text('status_notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('participant_discipline', 'reviewed_by')) {
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->after('status_notes')
                    ->constrained('users')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('participant_discipline', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('participant_discipline', function (Blueprint $table) {
            if (Schema::hasColumn('participant_discipline', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            if (Schema::hasColumn('participant_discipline', 'reviewed_by')) {
                $table->dropConstrainedForeignId('reviewed_by');
            }
            if (Schema::hasColumn('participant_discipline', 'status_notes')) {
                $table->dropColumn('status_notes');
            }
            if (Schema::hasColumn('participant_discipline', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
