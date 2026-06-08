<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartographerModifiedObject
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Model $object,
        public readonly array $dirty,
        public readonly string $objectType,
    ) {}
}
