<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $query->where('is_active', true);
    }

    public function scopeAvailableForAdoption($query)
    {
        return $query->active()->where('adoption_status', 'available');
    }

    public function hasUrgentHealthRecord(): bool
    {
        return $this->healthRecords()->where('severity', 'urgent')->exists();
    }
}
