<?php

namespace Karomap\LaravelAttachment\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

trait StoresAttachment
{
    /**
     * Store attachment.
     *
     * @param  UploadedFile $file
     * @param  int          $maxWidth
     * @param  int          $maxHeight
     * @return array
     */
    public function storeAttachment(UploadedFile $file, $maxWidth = 1920, $maxHeight = 1920)
    {
        $attachmentsDir = config('attachment.attachments_dir');
        $name = $file->getClientOriginalName();
        $filename = now()->format('YmdHis-').$name;
        $mime = $file->getMimeType();

        $path = null;
        if (!App::environment('testing') && Str::startsWith($mime, 'image/')) {
            try {
                $img = Image::read($file)
                    ->scaleDown($maxWidth, $maxHeight)
                    ->encodeByMediaType();
                $path = $attachmentsDir.'/'.$filename;
                Storage::put($path, $img->toFilePointer());
            } catch (\Throwable $th) {
                logger()->error($th->getMessage());
            }
        }

        if (!$path) {
            $path = $file->storeAs($attachmentsDir, $filename);
        }

        $size = Storage::size($path);

        return compact('name', 'path', 'mime', 'size');
    }

    /**
     * Get the validation rules used to validate attachment file.
     *
     * @param  bool  $required
     * @return array
     */
    protected function attachmentFileRules(bool $required = false): array
    {
        $rules = ['file', 'mimes:'.implode(',', config('attachment.allowed_extensions')), 'max:'.config('attachment.max_size')];

        if ($required) {
            $rules[] = 'required';
        }

        return $rules;
    }
}
