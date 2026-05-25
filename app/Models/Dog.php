<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dog extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelter_id',
        'name',
        'breed',
        'size',
        'weight_kg',
        'color',
        'sex',
        'birth_date',
        'intake_date',
        'intake_source',
        'kennel',
        'quarantine_status',
        'adoption_status',
        'adoption_fee',
        'story',
        'temperament',
        'good_with_kids',
        'good_with_pets',
        'photo_url',
        'is_active',
    ];

    protected $casts = [
        'good_with_kids' => 'boolean',
        'good_with_pets' => 'boolean',
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'intake_date' => 'date',
        'adoption_fee' => 'decimal:2',
        'weight_kg' => 'decimal:2',
    ];

    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class)->latest('recorded_at');
    }

    public function contactLogs()
    {
        return $this->hasMany(ContactLog::class)->latest('logged_at');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function assignments()
    {
        return $this->hasMany(CaretakerAssignment::class);
    }

    public function activeAssignment()
    {
        return $this->hasOne(CaretakerAssignment::class)->where('is_active', true);
    }

    public function caretakers()
    {
        return $this->belongsToMany(User::class, 'caretaker_assignments', 'dog_id', 'caretaker_id')
            ->withPivot(['assigned_by', 'assigned_at', 'ended_at', 'is_active', 'notes'])
            ->wherePivot('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where($this->qualifyColumn('is_active'), true);
    }

    public function scopeAvailableForAdoption($query)
    {
        return $query->active()->where('adoption_status', 'available');
    }

    public function getAgeLabelAttribute(): string
    {
        if (! $this->birth_date) {
            return 'Age unknown';
        }

        $months = (int) $this->birth_date->diffInMonths(now());

        if ($months < 12) {
            return max($months, 1).' mo';
        }

        return floor($months / 12).' yr';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->photo_url) {
            return null;
        }

        if (Str::startsWith($this->photo_url, ['http://', 'https://'])) {
            return $this->photo_url;
        }

        return Storage::url($this->photo_url);
    }

    public function hasUrgentHealthRecord(): bool
    {
        return $this->healthRecords()->where('severity', 'urgent')->exists();
    }
}
