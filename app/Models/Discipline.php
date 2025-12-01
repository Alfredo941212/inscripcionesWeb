<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'gender',
        'max_capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_capacity' => 'integer',
    ];

    public function participantProfiles()
    {
        return $this->belongsToMany(ParticipantProfile::class, 'participant_discipline')
            ->withTimestamps()
            ->withPivot('selected_at');
    }
}
