<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_name',
        'address',
        'email',
        'phone',
        'logo',
    ];

    protected $casts = [
        'website_name' => 'string',
        'address' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'logo' => 'string',
    ];
}
