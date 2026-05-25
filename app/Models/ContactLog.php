<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'dog_id',
        'caretaker_id',
        'contact_type',
        'duration_minutes',
        'ppe_used',
        'notes',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function caretaker()
    {
        return $this->belongsTo(User::class, 'caretaker_id');
    }
}
