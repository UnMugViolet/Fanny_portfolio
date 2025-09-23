<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Filters\Filterable;

class Category extends Model
{
    use Filterable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'name',
        'slug',
        'description',
        'order',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'name',
        'slug',
        'order',
        'created_at',
        'updated_at',
    ];

    /* ========== Relations ========== */

    /**
     * Get the projects that belong to this category.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

}
