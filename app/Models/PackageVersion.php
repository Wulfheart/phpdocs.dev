<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageVersion extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'serialized_created_at' => 'datetime',
    ];
}
