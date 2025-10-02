<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;

class Project extends Model
{
    use Attachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'order',
        'author_id',
    ];

    /**
     * The attributes that should be cast to native types.
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
        'title',
        'status',
        'order',
        'author_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'title',
        'status',
        'order',
        'author_id',
        'created_at',
        'updated_at',
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
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }

    /**
     * Get all the associated images with this project.
     * 
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany(
            Attachment::class,
            'attachmentable',
            'attachmentable'
        );
    }

    /**
     * Get only the images linked to the project (filtered by group)
     */
    public function getImagesAttribute()
    {
        return $this->attachments()->where('group', 'images')->get();
    }

    /**
     * Get only the thumbnail attachments (filtered by group)
     */
    public function getThumbnailAttribute()
    {
        return $this->attachments()->where('group', 'thumbnail')->get();
    }

    /**
     * Get the author of this project.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
