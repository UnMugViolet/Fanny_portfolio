<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Tool extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    /* ========== Relations ========== */

    /**
     * Get the projects that use this tool.
     */
    public function getProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
                    ->withTimestamps();
    }



}
