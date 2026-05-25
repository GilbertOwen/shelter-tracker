<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shelter_id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'is_active' => 'boolean',
        ];
    }

    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }

    public function assignments()
    {
        return $this->hasMany(CaretakerAssignment::class, 'caretaker_id');
    }

    public function assignedDogs()
    {
        return $this->belongsToMany(Dog::class, 'caretaker_assignments', 'caretaker_id', 'dog_id')
            ->withPivot(['assigned_by', 'assigned_at', 'ended_at', 'is_active', 'notes'])
            ->wherePivot('is_active', true);
    }

    public function contactLogs()
    {
        return $this->hasMany(ContactLog::class, 'caretaker_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'recorded_by');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'assigned_to');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCaretaker(): bool
    {
        return $this->role === 'caretaker';
    }

    public function isAdopter(): bool
    {
        return $this->role === 'adopter';
    }
}
