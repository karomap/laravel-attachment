<?php

namespace Karomap\LaravelAttachment;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Karomap\LaravelAttachment\Models\Attachment;
use Karomap\LaravelAttachment\Policies\AttachmentPolicy;

class AttachmentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/attachment.php' => config_path('attachment.php'),
        ], 'attachment-config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_attachments_table.php' => $this->getMigrationFileName('create_attachments_table.php'),
        ], 'attachment-migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/attachment.php', 'attachment');

        // Register policies
        $this->booting(function () {
            Gate::policy(Attachment::class, AttachmentPolicy::class);
        });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    protected function getMigrationFileName($migrationFileName, $index = 0): string
    {
        $timestamp = date('Y_m_d_Hi').Str::padLeft((string) $index, 2, '0');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
