<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'role',
        'department',
        'location',
        'position',
        'bu',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }
    // User.php
public function isAdmin()
{
    return $this->role === 'admin'; // adjust if you use is_admin boolean
}



    /**
     * Accessor for display name.
     * Example:
     *  - "Name Last Name Cruz" => "NLastName"
     *  - "Name Secondname Last Name" => "NSLastName"
     *  -"Name Lastname" => "NLastname
     */
    public function getDisplayNameAttribute()
    {
        $initials = '';

        if ($this->name) {
            // We will take first letter of each word in the first name
            $firstNames = preg_split('/\s+/', trim($this->name));
            foreach ($firstNames as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        // Remove spaces from lastname but keep proper casing
        $lastName = $this->lastname ? str_replace(' ', '', ucwords(strtolower($this->lastname))) : '';

        return $initials . $lastName;
    }
}
