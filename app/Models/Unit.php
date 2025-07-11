<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Unit extends Model
{
    protected $fillable = ['name', 'abbreviation', 'description'];
}
