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
        $root = StackFolder::whereNull('parent_id')->firstOrFail();
        return redirect($root->url_hash);
    }

    /**
     * Show the requested folder page.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function folder($hash)
    {
        $folder = StackFolder::where('path_hash', $hash)->first();
        if (!isset($folder)) {
            $root = StackFolder::whereNull('parent_id')->firstOrFail();
            $root->refreshRecursiveUntilHashFound($hash);
            $folder = StackFolder::where('path_hash', $hash)->firstOrFail();
        }

        $folder->refresh();

        return view('folder', [
            'item' => $folder,
        ]);
    }

    /**
     * Show the requested file page, or direct file.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function file($hash, $name = null)
    {
        $file = StackFile::where('path_hash', $hash)->first();
        if (!isset($file)) {
            $root = StackFolder::whereNull('parent_id')->firstOrFail();
            $root->refreshRecursiveUntilHashFound($hash);
            $file = StackFile::where('path_hash', $hash)->firstOrFail();
        }

        else {
            $file->parent->refresh();
        }

        if ($name != null) {
            if (request()->has('dl')) {
                return response()->stackDownload($file);
            }
            else {
                return response()->stackView($file);
            }
        }

        else {
            return view('file', [
                'item' => $file,
            ]);
        }
    }
}
