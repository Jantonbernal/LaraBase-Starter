<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\HasStatus;
use App\Traits\SerializableDate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, SerializableDate, HasStatus;

    protected $fillable = [
        'name',
        'status'
    ];

    // cast for status attribute
    protected $casts = [
        'status' => Status::class,
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            // Al guardar, siempre a mayúsculas
            set: fn(string $value) => strtoupper($value),
        );
    }
}
