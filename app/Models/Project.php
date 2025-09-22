<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'author_id',
    ];


    /* ========== Relations ========== */


    /**
     * Get the categories associated with this project.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the tools that are used in this project.
     */
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)
                    ->withTimestamps();
    }

    /**
     * Get the author of this project.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
