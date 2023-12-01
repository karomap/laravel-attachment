<?php

namespace Karomap\LaravelAttachment\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Karomap\LaravelAttachment\Models\Attachment;

class MediaController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $path
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $path)
    {
        $attachment = Attachment::where('path', $path)->firstOrFail();
        abort_if(!Storage::exists($attachment->path), 404);

        if ($request->user()) {
            $this->authorize('view', $attachment);
        } else {
            abort_if($attachment->is_private, 403);
        }

        $abspath = Storage::path($attachment->path);
        $headers = ['Content-Type' => $attachment->mime];

        if ($request->download) {
            $filename = File::basename($abspath);

            return response()->download($abspath, $filename, $headers);
        }

        return response()->file($abspath, $headers);
    }
}
