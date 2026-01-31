<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $fillable = [
        'path',
        'name',
        'mime_type',
        'uploaded_by',
        'status',
    ];

    // cast for status attribute
    protected $casts = [
        'status' => Status::class,
    ];
}
