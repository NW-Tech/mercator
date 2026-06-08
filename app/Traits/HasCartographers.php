<?php

namespace App\Traits;

use App\Models\Cartographer;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCartographers
{
    public function cartographers(): MorphMany
    {
        return $this->morphMany(Cartographer::class, 'cartographiable');
    }
}
