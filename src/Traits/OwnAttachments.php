<?php

namespace Karomap\LaravelAttachment\Traits;

use Karomap\LaravelAttachment\Models\Attachment;

trait OwnAttachments
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownAttachments()
    {
        return $this->hasMany(Attachment::class, 'user_id');
    }
}
