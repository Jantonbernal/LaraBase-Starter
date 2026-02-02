<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\SerializableDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, SerializableDate;

    protected $fillable = [
        'slug',
        'name',
        'status',
    ];

    // cast for status attribute
    protected $casts = [
        'status' => Status::class,
    ];

    /**
     * The users that belong to the permission.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
