<?php

namespace App\Http\Controllers;

use App\StackFile;
use App\StackFolder;

class BrowseController extends Controller
{
    /**
     * Handle the users request for a path.
     *
     * @param string $path
     * @return \Illuminate\Http\Response
     */
    public function request($path = '')
    {
        $path = '/' . $path;

        // Try to grab as file
        $file = StackFile::where('path_slug', $path)->first();
        if (isset($file)) {
            return $this->file($file);
        }

        // Try to grab as folder
        else {
            $folder = StackFolder::where('path_slug', $path)->firstOrFail();
            return $this->folder($folder);
        }
    }

    /**
     * Show the requested folder for a hash.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function folderHash($hash)
    {
        return $this->folder(StackFolder::where('path_hash', $hash)->firstOrFail());
    }

    /**
     * Show the requested file for a hash.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function fileHash($hash)
    {
        return $this->file(StackFile::where('path_hash', $hash)->firstOrFail());
    }

    /**
     * Show the requested folder.
     *
     * @param StackFolder $folder
     * @return \Illuminate\Http\Response
     */
    public function folder(StackFolder $folder)
    {
        $folder->refresh();

        return view('folder', [
            'item' => $folder,
        ]);
    }

    /**
     * Show the requested file.
     *
     * @param StackFile $file
     * @return \Illuminate\Http\Response
     */
    public function file(StackFile $file)
    {
        if (request()->has('full') || request()->has('dl')) {
            $stack = resolve('App\Stack\StackApi');
            $stack->presentFile($file, request()->has('dl'));
            return null;
        }

        elseif (request()->has('thumbnail')) {
            $stack = resolve('App\Stack\StackApi');
            $stack->presentThumbnail($file);
            return null;
        }

        else {
            return view('file', [
                'item' => $file,
            ]);
        }
    }
}
