<?php

namespace App\Models;

use App\SerializableDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /** @use HasFactory<\Database\Factories\LogFactory> */
    use HasFactory, SerializableDate;

    protected $fillable = [
        'user_id',
        'route',
        'method',
        'message',
        'payload',
    ];

    /**
     * Get the user that owns the Branch.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
