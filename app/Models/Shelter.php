<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'email',
        'capacity',
        'description',
        'contact_for_adoption',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function dogs()
    {
        return $this->hasMany(Dog::class);
    }
}
