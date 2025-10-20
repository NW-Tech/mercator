<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneAdmin extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'zone_admins';

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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function annuaires(): HasMany
    {
        return $this->hasMany(Annuaire::class, 'zone_admin_id', 'id')->orderBy('name');
    }

    public function forestAds(): HasMany
    {
        return $this->hasMany(ForestAd::class, 'zone_admin_id', 'id')->orderBy('name');
    }
}
