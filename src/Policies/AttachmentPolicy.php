<?php

namespace Karomap\LaravelAttachment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Karomap\LaravelAttachment\Models\Attachment;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User                      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny($user)
    {
        return $this->hasWriteAccess($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User                             $user
     * @param  \Karomap\LaravelAttachment\Models\Attachment $attachment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view($user, Attachment $attachment)
    {
        if (!$attachment->is_private || $this->hasWriteAccess($user, $attachment)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User                      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create($user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User                             $user
     * @param  \Karomap\LaravelAttachment\Models\Attachment $attachment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update($user, Attachment $attachment)
    {
        return $this->hasWriteAccess($user, $attachment);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User                             $user
     * @param  \Karomap\LaravelAttachment\Models\Attachment $attachment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete($user, Attachment $attachment)
    {
        return $this->hasWriteAccess($user, $attachment);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User                      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function batchDelete($user)
    {
        return $this->hasWriteAccess($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User                             $user
     * @param  \Karomap\LaravelAttachment\Models\Attachment $attachment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore($user, Attachment $attachment)
    {
        return $this->hasWriteAccess($user, $attachment);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User                             $user
     * @param  \Karomap\LaravelAttachment\Models\Attachment $attachment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete($user, Attachment $attachment)
    {
        return $this->hasWriteAccess($user, $attachment);
    }

    protected function hasWriteAccess($user, Attachment $attachment = null): bool
    {
        $granted = true;
        $writeAccessRoles = config('attachment.write_access_roles', []);

        if (!empty($writeAccessRoles) && method_exists($user, 'hasRole')) {
            $granted = $user->hasRole($writeAccessRoles);
        }

        if ($attachment != null) {
            return $attachment->user_id == $user->id || $granted;
        }

        return $granted;
    }
}
