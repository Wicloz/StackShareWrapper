<?php

namespace App\Http\Controllers;

use App\StackFile;
use App\StackFolder;

class BrowseController extends Controller
{
    /**
     * Show the root folder.
     *
     * @return \Illuminate\Http\Response
     */
    public function root()
    {
        $folder = StackFolder::whereNull('parent_id')->firstOrFail();
        return redirect($folder->url_hash);
    }

    /**
     * Show the requested folder.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function folder($hash)
    {
        $folder = StackFolder::where('path_hash', $hash)->firstOrFail();
        $folder->refresh();

        return view('folder', [
            'item' => $folder,
        ]);
    }

    /**
     * Show the requested file.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function file($hash)
    {
        $file = StackFile::where('path_hash', $hash)->firstOrFail();

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
