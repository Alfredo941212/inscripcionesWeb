<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParticipantProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birthdate',
        'curp',
        'seniority_years',
        'constancia_path',
        'cfdi_path',
        'photo_path',
        'status',
        'status_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'seniority_years' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(Discipline::class, 'participant_discipline')
            ->withTimestamps()
            ->withPivot([
                'selected_at',
                'status',
                'status_notes',
                'reviewed_by',
                'reviewed_at',
            ]);
    }
}
