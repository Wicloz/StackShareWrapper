<?php

namespace App;

class StackFile extends StackItem
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'parent', 'mimetype'];

    /**
     * Get the parent folder for this file.
     */
    public function parent()
    {
        return $this->belongsTo('App\StackFolder');
    }

    /**
     * @return string
     */
    public function getTypeAttribute()
    {
        if ($this->mimetype === 'application/octet-stream') {
            return 'video';
        }

        return explode('/', $this->mimetype)[0];
    }

    /**
     * @return string
     */
    public function getPreviewThumbAttribute()
    {
        switch ($this->type) {
            case 'image':
                $baseurl = config('stack.baseurl');
                $shareid = config('stack.shareid');
                return $baseurl . '/public-share/' . $shareid . '/preview?path=' . $this->path . '&mode=thumbnail';

            default:
                return '';
        }

    }

    /**
     * @return string
     */
    public function getPreviewFullAttribute()
    {
        $baseurl = config('stack.baseurl');
        $shareid = config('stack.shareid');

        return $baseurl . '/public-share/' . $shareid . '/preview?path=' . $this->path . '&mode=full';
    }
}
