<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ForestAd
 */
class ForestAd extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'forest_ads';

    public static $searchable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'zone_admin_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function zone_admin(): BelongsTo
    {
        return $this->belongsTo(ZoneAdmin::class, 'zone_admin_id');
    }

    public function domaines(): BelongsToMany
    {
        return $this->belongsToMany(DomaineAd::class)->orderBy('name');
    }
}
