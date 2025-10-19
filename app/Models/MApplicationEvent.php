<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MApplicationEvent extends Model
{
    use HasFactory;

    public $table = 'm_application_events';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'm_application_id',
        'message',
        'created_at',
        'updated_at',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(MApplication::class, 'm_application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
