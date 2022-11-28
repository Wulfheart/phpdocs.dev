<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageVersion extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'cached_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function alreadyAnalyzed(): bool
    {
        return $this->cached_at !== null && $this->cached_at->lessThan(now());
    }

    public function isDevVersion(): bool {
        return str_contains($this->name, 'dev');
    }
}
