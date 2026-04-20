<?php

namespace App\Models;

use Wirechat\Wirechat\Traits\InteractsWithWirechat;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Wirechat\Wirechat\Panel;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    use InteractsWithWirechat;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'telephone',
        'statut'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function halaqas()
    {
        return $this->hasMany(Halaqa::class, 'cheikh_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'cheikh_id');
    }

    public function cheikh()
    {
        return $this->hasOne(Cheikh::class);
    }

    /**
     * Authorize chat creation in WireChat.
     */
    public function canCreateChats(): bool
    {
        return $this->statut === 'active';
    }

    /**
     * Authorize group creation in WireChat.
     */
    public function canCreateGroups(): bool
    {
        return $this->statut === 'active';
    }

    /**
     * Authorize access to the WireChat panel.
     */
    public function canAccessWirechatPanel(Panel $panel): bool
    {
        return $this->statut === 'active';
    }

    /**
     * Display name used by WireChat UI.
     */
    public function getWirechatNameAttribute(): string
    {
        $fullName = trim(($this->prenom ?? '').' '.($this->nom ?? ''));

        return $fullName !== '' ? $fullName : ($this->email ?? 'Utilisateur');
    }
    
}
