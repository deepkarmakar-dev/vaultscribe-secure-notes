<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

// Filament ki saari imports hata di hain 

class User extends Authenticatable 
{
    use HasFactory, Notifiable;

    protected $table = "users";

    // canAccessPanel function ko delete kar diya hai 

    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'otp_expires_at',
        'email_verified_at',
        'is_verified',
        'google2fa_secret',
        'google2fa_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at'    => 'datetime',
            'password'          => 'hashed',
            'google2fa_enabled' => 'boolean',
        ];
    }

    //  Safe encryption for 2FA secret 
    protected function google2faSecret(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ? decrypt($value) : null,
            set: fn ($value) => $value ? encrypt($value) : null,
        );
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}