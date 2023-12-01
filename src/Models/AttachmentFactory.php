<?php

namespace Karomap\LaravelAttachment\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Karomap\LaravelAttachment\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $attachmentsDir = config('attachment.attachments_dir');
        $userClass = config('attachment.user_model');
        $name = $this->faker->unique()->words(2, true);
        $filename = now()->format('YmdHis').'-'.Str::slug($name).'.png';
        $path = $attachmentsDir.'/'.$filename;
        $mime = 'image/png';
        $size = $this->faker->randomNumber();

        return [
            'user_id' => $userClass::first() ?? $userClass::factory(),
            'name' => $name,
            'path' => $path,
            'mime' => $mime,
            'size' => $size,
            'is_private' => false,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Attachment $model) {
            $image = Image::canvas(800, 600, $this->faker->hexColor())
                ->text($model->name, 400, 300, function ($font) {
                    $font->file(__DIR__.'/../fonts/Roboto_regular.ttf');
                    $font->size(64);
                    $font->align('center');
                    $font->valign('center');
                    $font->angle(40);
                });
            Storage::put($model->path, $image->stream());
        });
    }

    /**
     * Indicate that the attachment is private.
     *
     * @return static
     */
    public function private()
    {
        return $this->state(function (array $attributes) {
            return ['is_private' => true];
        });
    }
}
