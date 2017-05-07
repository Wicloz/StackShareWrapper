<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrowseController extends Controller
{
    /**
     * Handle the users request for a path.
     *
     * @return \Illuminate\Http\Response
     */
    public function request($path = '')
    {
        if (true) {
            return $this->folder($path);
        } else {
            return $this->file($path);
        }
    }

    /**
     * Show the folder at the given path.
     *
     * @return \Illuminate\Http\Response
     */
    public function folder($path)
    {
        return view('folder', [
            'path' => $path,
        ]);
    }

    /**
     * Show the file at the given path.
     *
     * @return \Illuminate\Http\Response
     */
    public function file($path)
    {
        return view('file', [
            'path' => $path,
        ]);
    }
}
