<?php

namespace App\Models;

use App\Enums\SectionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'description',
        'full_description',
        'chairs',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'status' => SectionStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function canRegistration(): bool
    {
        return $this->status === SectionStatus::REGISTRATION;
    }

    public function canCreateThesis(): bool
    {
        return $this->status === SectionStatus::THESIS_SUBMISSION;
    }

    public function hasParticipant(?User $user): bool
    {
        if (!$user) return false;
        return $this->users()->where('user_id', $user->id)->exists();
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['topic', 'supervisor', 'co_author', 'degree_type', 'course', 'group_number', 'phone_number', 'description', 'topics'])
            ->withTimestamps();
    }
}
