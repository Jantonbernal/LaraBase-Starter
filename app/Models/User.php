<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Status;
use App\Traits\HasCode;
use App\Traits\SerializableDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    use SerializableDate, HasCode;

    const CODE_PREFIX = 'USU';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'file_id',
        'status',
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
            'status' => Status::class,
        ];
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * The permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get the file that owns the user.
     */
    public function photo()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    /**
     * Determine if the user has a given permission.
     */
    public function hasPermission(string $slug): bool
    {
        // Verificamos si el slug existe en sus permisos directos
        // O si existe en los permisos de cualquiera de sus roles
        return $this->permissions()->where('slug', $slug)->exists() ||
            $this->roles()->whereHas('permissions', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->exists();
    }
}
