<?php

namespace App;

use App\Stack\Downloaders;

/**
 * App\StackFile
 *
 * @property int $id
 * @property string $path
 * @property string $path_slug
 * @property string $path_hash
 * @property string $mimetype
 * @property int $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read array $breadcrumbs
 * @property-read string $name
 * @property-read string $path_clean
 * @property-read string $preview_full
 * @property-read string $preview_thumb
 * @property-read string $type
 * @property-read string $size
 * @property-read \App\StackFolder $parent
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePathHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePathSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StackFile extends StackItem
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'parent', 'mimetype'];

    /**
     * Extensions for files that can be previewed as code.
     *
     * @var array
     */
    protected $codeExtensions = [
        'php',
        'sh',
        'bat',
        'php',
        'js',
        'cs',
        'cpp',
        'c++',
    ];

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
    public function getMimetypeAttribute()
    {
        return filenameToMimeType($this->name);
    }

    /**
     * @return string
     */
    public function getTypeAttribute()
    {
        $bits = explode('.', $this->name);

        if (count($bits) > 1 && $bits[count($bits) - 1] === 'md') {
            return 'markdown';
        }

        elseif (count($bits) > 1 && in_array($bits[count($bits) - 1], $this->codeExtensions)) {
            return 'code';
        }

        elseif ($this->mimetype === 'application/json') {
            return 'json';
        }

        elseif ($this->mimetype === 'application/x-msdownload') {
            return 'executable';
        }

        else {
            return explode('/', $this->mimetype)[0];
        }
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
                return "{$baseurl}/public-share/{$shareid}/preview?path={$this->path}&mode=thumbnail";

            default:
                if (file_exists(public_path("/media/thumbnails/{$this->type}.svg"))) {
                    return url("/media/thumbnails/{$this->type}.svg");
                } else {
                    return url("/media/thumbnails/file.svg");
                }

        }

    }

    /**
     * @return string
     */
    public function getPreviewFullAttribute()
    {
        $baseurl = config('stack.baseurl');
        $shareid = config('stack.shareid');

        return "{$baseurl}/public-share/{$shareid}/preview?path={$this->path}&mode=full";
    }

    /**
     * @return float|null
     */
    public function getSizeAttribute()
    {
        return Downloaders::getFileSize($this->download_remote);
    }
}
