<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'dog_id',
        'schedule_id',
        'user_id',
        'performed_at',
        'notes',
        'photo_url',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
