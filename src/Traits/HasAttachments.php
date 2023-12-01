<?php

namespace Karomap\LaravelAttachment\Traits;

use Karomap\LaravelAttachment\Models\Attachment;

trait HasAttachments
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attachments()
    {
        return $this->morphToMany(Attachment::class, 'attachable');
    }
}
