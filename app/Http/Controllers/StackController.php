<?php

namespace App\Http\Controllers;

use App\StackFile;
use App\StackFolder;
use Illuminate\Http\UploadedFile;

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
        ]);

        // Create full path and get parent folder
        $path = request()->folder;
        if (!starts_with($path, '/')) {
            $path = '/' . $path;
        }
        $parentFolder = StackFolder::where('path', $path)->first();
        if (!ends_with($path, '/')) {
            $path = $path . '/';
        }
        $path .= request()->file->getClientOriginalName();

        // Check for StackFile existence
        $file = $parentFolder->subFiles()->where('path', $path)->first();
        if ($file !== null) {
            return response()->json([
                'success' => false,
                'response' => 'File already exists',
            ]);
        }

        // Create new StackFile
        else {
            $file = new StackFile([
                'path' => $path,
                'size' => request()->file->getClientSize(),
                'mimetype_remote' => request()->file->getClientMimeType(),
            ]);
        }

        // Upload file to stack
        $stack = resolve('App\Stack\StackApi');
        $response = $stack->uploadFile($file->path, request()->file->path());

        // Send failed JSON response
        if ($response !== 'Created') {
            return response()->json([
                'success' => false,
                'response' => trim($response),
            ]);
        }

        // Send successfull JSON response and save StackFile
        else {
            $parentFolder->subFiles()->save($file);
            return response()->json([
                'success' => true,
                'response' => trim($response),
                'shareUrl' => url("/file/{$file->path_hash}?full=1"),
                'thumbnailUrl' => $file->file_thumbnail,
            ]);
        }
    }
}
