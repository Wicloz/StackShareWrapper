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
     * Redirect to the requested folder for a hash.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function folderHash($hash)
    {
        return redirect(url(StackFolder::where('path_hash', $hash)->firstOrFail()->path_slug . encodeRequestToGet(request())), 302, request()->headers->all());
    }

    /**
     * Redirect to the requested file for a hash.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function fileHash($hash)
    {
        return redirect(url(StackFile::where('path_hash', $hash)->firstOrFail()->path_slug . encodeRequestToGet(request())), 302, request()->headers->all());
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

        else {
            return view('file', [
                'item' => $file,
            ]);
        }
    }
}
