<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaretakerAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dog_id',
        'caretaker_id',
        'assigned_by',
        'assigned_at',
        'ended_at',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function caretaker()
    {
        return $this->belongsTo(User::class, 'caretaker_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
