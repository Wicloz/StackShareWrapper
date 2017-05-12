<?php

namespace App;

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
 * @property-read \App\StackFolder $parent
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereMimetype($value)
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
            return '';
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
                return "{$baseurl}/public-share/{$shareid}/preview?path={$this->path}&mode=thumbnail";

            default:
                return url("/media/thumbnails/{$this->type}.svg");
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
}
