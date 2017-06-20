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
     * Show the requested folder.
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
     * Show the requested file.
     *
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function file($hash)
    {
        $cleanHash = explode('.', $hash)[0];
        $file = StackFile::where('path_hash', $cleanHash)->first();
        if (!isset($file)) {
            $root = StackFolder::whereNull('parent_id')->firstOrFail();
            $root->refreshRecursiveUntilHashFound($cleanHash);
            $file = StackFile::where('path_hash', $cleanHash)->firstOrFail();
        }

        if (str_contains($hash, '.')) {
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
