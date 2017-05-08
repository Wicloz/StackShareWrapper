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
            'path' => '/' . $path,
            'pathObjects' => $this->pathToObjects($path),
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
            'path' => '/' . $path,
            'pathObjects' => $this->pathToObjects($path),
        ]);
    }

    /**
     * Turn a path string into an array of more informative objects.
     *
     * @return array
     */
    private function pathToObjects($path) {
        if (empty($path) || $path === '/') {
            return [];
        }

        else {
            $objects = [];

            foreach (explode('/', $path) as $part) {
               $newObject = new \stdClass();

               $newObject->name = $part;
               $newObject->pathTo = (count($objects) > 0 ? $objects[count($objects) - 1]->pathTo : '') . '/' . $part;

               $objects[] = $newObject;
            }

            return $objects;
        }
    }
}
