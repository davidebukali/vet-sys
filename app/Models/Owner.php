<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\OwnerTestFactory;
use App\Models\Patient;

class Owner extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): OwnerTestFactory
    {
        return OwnerTestFactory::new();
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}
