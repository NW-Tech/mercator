<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Process
 */
class Process extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'processes';

    public static $searchable = [
        'name',
        'description',
        'icon_id',
        'in_out',
        'owner',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'in_out',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'security_need_auth',
        'owner',
        'macroprocess_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function information(): BelongsToMany
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
    }

    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class)->orderBy('name');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'process_id', 'id')->orderBy('name');
    }

    public function dataProcesses(): BelongsToMany
    {
        return $this->belongsToMany(DataProcessing::class, 'data_processing_process')->orderBy('name');
    }

    public function macroProcess(): BelongsTo
    {
        return $this->belongsTo(MacroProcessus::class, 'macroprocess_id');
    }

    public function securityControls(): BelongsToMany
    {
        return $this->belongsToMany(SecurityControl::class, 'security_control_process')->orderBy('name');
    }
}
