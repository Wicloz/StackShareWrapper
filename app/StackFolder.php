<?php

namespace App;

use App\Stack\Downloader;

class StackFolder extends StackItem
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'parent'];

    /**
     * Get the parent folder for this folder.
     */
    public function parent()
    {
        return $this->belongsTo('App\StackFolder');
    }

    /**
     * Get all folders in this folder.
     */
    public function subFolders()
    {
        return $this->hasMany('App\StackFolder', 'parent_id');
    }

    /**
     * Get all files in this folder.
     */
    public function subFiles()
    {
        return $this->hasMany('App\StackFile', 'parent_id');
    }

    /**
     * Refresh all the data for this folder from stack.
     */
    public function refresh()
    {
        $json = Downloader::downloadList($this->path);

        foreach ($json->nodes as $node) {
            if ($node->mimetype === 'httpd/unix-directory') {
                $this->subFolders()->updateOrCreate([
                    'path' => $node->path,
                ],[]);
            }

            else {
                $this->subFiles()->updateOrCreate([
                    'path' => $node->path,
                ],[
                    'mimetype' => $node->mimetype,
                ]);
            }
        }

        $this->subFolders()->whereNotIn('path',  collect($json->nodes)->pluck('path'))->delete();
        $this->subFiles()->whereNotIn('path',  collect($json->nodes)->pluck('path'))->delete();
    }
}
