<?php

namespace App\Http\Controllers;

use App\StackFile;
use App\StackFolder;

class StackController extends Controller
{
    /**
     * Handle a file upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        // Basic request validation
        $this->validate(request(), [
            'folder' => 'required|string',
            'file' => 'required|file',
            'token' => 'required|string',
        ]);

        // Authorize given token
        if (request()->token !== config('auth.tokens.upload')) {
            return response()->json([
                'success' => false,
                'response' => 'Unauthorized token',
            ], 403);
        }

        // Create full path and get parent folder
        $path = request()->folder;
        if (!starts_with($path, '/')) {
            $path = '/' . $path;
        }
        while (ends_with($path, '/')) {
            $path = str_replace_last('/', '', $path);
        }
        $parentFolder = StackFolder::where('path', $path)->first();
        $path .= '/' . request()->file->getClientOriginalName();

        // Refresh the cache for the parent folder
        $parentFolder->refresh();

        // Check for StackFile existence
        $file = $parentFolder->subFiles()->where('path', $path)->first();
        if ($file !== null) {
            return response()->json([
                'success' => false,
                'response' => 'File already exists',
            ], 409);
        }

        // Create new StackFile
        $file = new StackFile([
            'path' => $path,
            'size' => request()->file->getClientSize(),
            'mimetype_remote' => request()->file->getClientMimeType(),
        ]);

        // Upload file to stack
        $stack = resolve('App\Stack\StackApi');
        $response = $stack->uploadFile($file->path, request()->file->path());

        // Send failed JSON response
        if ($response !== 'Created') {
            $status = 520;
            if (!empty(intval($response))) {
                $status = intval($response);
            }
            return response()->json([
                'success' => false,
                'response' => trim($response),
            ], $status);
        }

        // Send successful JSON response and save StackFile
        $parentFolder->subFiles()->save($file);
        return response()->json([
            'success' => true,
            'response' => trim($response),
            'shareUrl' => $file->url_full,
            'thumbnailUrl' => $file->file_thumbnail,
        ]);
    }
}
