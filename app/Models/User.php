<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'worker_number',
        'phone',
        'role',
        'password',
        'hash_constancia',
        'hash_cfdi',
        'hash_foto',
        'firma_digital',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function participantProfile()
    {
        return $this->hasOne(ParticipantProfile::class);
    }
    public function profile()
    {
        return $this->hasOne(ParticipantProfile::class, 'user_id');
    }

    public function reviewedParticipants()
    {
        return $this->hasMany(ParticipantProfile::class, 'reviewed_by');
    }
}
