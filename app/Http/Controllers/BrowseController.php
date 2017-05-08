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
        // Explode the path properly
        if ($path === '/' || $path === '') {
            $path = [];
        } else {
            $path = explode('/', $path);
        }

        // Grab the root folder (or fail)
        $folder = StackFolder::whereNull('name')->whereNull('parent_id')->firstOrFail();

        foreach ($path as $index => $pathBit) {
            if ($index === count($path) - 1) {
                // At the last bit, try to find a file
                $file = $folder->subFiles()->where('name', $pathBit)->first();
            }
            if (!isset($file)) {
                // If no file was found, grab the sub folder (or fail)
                $folder = $folder->subFolders()->where('name', $pathBit)->firstOrFail();
            }
        }

        if (isset($file)) {
            return $this->file($file);
        } else {
            return $this->folder($folder);
        }
    }

    /**
     * Show the requested folder.
     *
     * @param StackFolder $folder
     * @return \Illuminate\Http\Response
     */
    public function folder(StackFolder $folder)
    {
        return view('folder', [
            'folder' => $folder,
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
        return view('file', [
            'file' => $file,
        ]);
    }
}
