<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'dog_id',
        'recorded_by',
        'observation',
        'severity',
        'symptoms',
        'zoonosis_flag',
        'photo_url',
        'notes',
        'recorded_at',
    ];

    protected $casts = [
        'zoonosis_flag' => 'boolean',
        'recorded_at' => 'datetime',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function isUrgent(): bool
    {
        return $this->severity === 'urgent';
    }
}
