<?php

namespace App\Models;

use App\SerializableDate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, SerializableDate;

    protected $fillable = [
        'business_name',
        'trade_name',
        'document',
        'email',
        'phone_number',
        'file_id',
    ];

    public function logo()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    protected function businessName(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtoupper($value),
        );
    }

    protected function tradeName(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtoupper($value),
        );
    }
}
