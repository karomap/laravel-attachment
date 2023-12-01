<?php

namespace Karomap\LaravelAttachment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @OA\Schema(
 *      title="Attachment",
 *      @OA\Property(property="id", type="integer", readOnly=true),
 *      @OA\Property(property="user_id", type="integer", readOnly=true),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="description", type="string"),
 *      @OA\Property(property="path", type="string", readOnly=true),
 *      @OA\Property(property="mime", type="string", readOnly=true),
 *      @OA\Property(property="size", type="integer", readOnly=true),
 *      @OA\Property(property="is_private", type="boolean"),
 *      @OA\Property(property="created_at", type="datetime", readOnly=true),
 *      @OA\Property(property="updated_at", type="datetime", readOnly=true),
 *      @OA\Property(property="url", type="string", readOnly=true),
 * )
 */
class Attachment extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    protected $appends = [
        'url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            try {
                Storage::delete($model->path);
            } catch (\Throwable $th) {
                unset($th);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logUnguarded();
    }

    protected static function newFactory()
    {
        return AttachmentFactory::new();
    }

    /**
     * ========================================
     * RELATIONSHIPS
     * ========================================.
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('attachment.user_model'), 'user_id');
    }

    /**
     * ========================================
     * ACCESSORS
     * ========================================.
     */

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
