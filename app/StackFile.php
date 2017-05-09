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
 * @return bool
 */
    public function getCanPreviewAttribute()
    {
        return $this->mime_min === 'image' || $this->mime_min === 'video' || $this->mime_min === 'audio';
    }

    /**
     * @return bool
     */
    public function getCanThumbnailAttribute()
    {
        return $this->mime_min === 'image';
    }

    /**
     * @return string
     */
    public function getMimeMinAttribute()
    {
        return explode('/', $this->mimetype)[0];
    }

    /**
     * @return string
     */
    public function getPreviewThumbAttribute()
    {
        $baseurl = config('stack.baseurl');
        $shareid = config('stack.shareid');

        return $baseurl . '/public-share/' . $shareid . '/preview?path=' . $this->path . '&mode=thumbnail';
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
