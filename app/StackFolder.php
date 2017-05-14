<?php

namespace App;

use App\Stack\Downloaders;
use App\Stack\StackApi;

/**
 * App\StackFolder
 *
 * @property int $id
 * @property string $path
 * @property string $path_slug
 * @property string $path_hash
 * @property int $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read array $breadcrumbs
 * @property-read string $file_thumbnail
 * @property-read string $name
 * @property-read string $path_clean
 * @property-read \App\StackFolder $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\StackFile[] $subFiles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\StackFolder[] $subFolders
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder wherePathHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder wherePathSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
     * @return string
     */
    public function getFileThumbnailAttribute()
    {
        return url('/media/thumbnails/folder.svg');
    }

    /**
     * Refresh all the data for this folder from stack.
     */
    public function refresh()
    {
        $stack = resolve('App\Stack\StackApi');
        $json = $stack->getFolderInfo($this->path);

        foreach ($json->nodes as $node) {
            if ($node->mimetype === 'httpd/unix-directory') {
                $this->subFolders()->updateOrCreate([
                    'path' => $node->path,
                ], []);
            }

            else {
                $this->subFiles()->updateOrCreate([
                    'path' => $node->path,
                ], [
                    'mimetype_remote' => $node->mimetype,
                ]);
            }
        }

        $this->subFolders()->whereNotIn('path', collect($json->nodes)->pluck('path'))->delete();
        $this->subFiles()->whereNotIn('path', collect($json->nodes)->pluck('path'))->delete();
    }
}
