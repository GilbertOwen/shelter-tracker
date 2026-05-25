<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'dog_id',
        'assigned_to',
        'type',
        'title',
        'description',
        'start_time',
        'duration_minutes',
        'priority',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->start_time->isPast();
    }
}
