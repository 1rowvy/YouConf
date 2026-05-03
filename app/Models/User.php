<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use MustVerifyEmailTrait;

    protected $fillable = [
        'vk_id',
        'first_name',
        'last_name',
        'patronymic',
        'avatar',
        'email',
        'email_verified_at',
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail);
    }

    public function getRoleIdAttribute()
    {
        return $this->roles->first()?->id;
    }

    public function setRoleIdAttribute($value)
    {
        $this->syncRoles(Role::find($value));
    }
    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    public function getFullNameAttribute()
    {
        return implode(' ', array_filter([
            $this->last_name,
            $this->first_name,
            $this->patronymic,
        ]));
    }
}
