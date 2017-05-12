<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        if (request()->has('dl')) {
            header("accept-ranges: bytes");
            header("content-disposition: attachment; filename=\"{$file->name}\"");
            header("content-length: {$file->size}");
            header("content-type: {$file->mimetype}");
            readfile(cleanUrl($file->preview_full));
            return null;
        }

        elseif (request()->has('full')) {
            header("accept-ranges: bytes");
            header("content-disposition: filename=\"{$file->name}\"");
            header("content-length: {$file->size}");
            header("content-type: {$file->mimetype}");
            readfile(cleanUrl($file->preview_full));
            return null;
        }

        else {
            return view('file', [
                'item' => $file,
            ]);
        }
    }
}
