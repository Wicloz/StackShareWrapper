<?php

namespace App;

use App\Stack\Downloaders;
use App\Stack\StackApi;

/**
 * App\StackFolder
 *
 * @property int $id
 * @property string $path
 * @property string $path_hash
 * @property int $size
 * @property int $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read array $parents
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
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFolder whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $human_size
 * @property-read string $url_hash
 */
class StackFolder extends StackItem
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'size', 'parent'];

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
        return '/media/thumbnails/folder.svg';
    }

    /**
     * Refresh all the data for this folder from stack.
     */
    public function refresh()
    {
        $stack = resolve('App\Stack\StackApi');
        $nodes = $stack->getFolderInfo($this->path);

        foreach ($nodes as $node) {
            if ($node->mimetype === 'httpd/unix-directory') {
                $this->subFolders()->updateOrCreate([
                    'path' => $node->path,
                ], [
                    'size' => $node->fileSize,
                ]);
            }

            else {
                $this->subFiles()->updateOrCreate([
                    'path' => $node->path,
                ], [
                    'mimetype_remote' => $node->mimetype,
                    'size' => $node->fileSize,
                ]);
            }
        }

        $this->subFolders()->whereNotIn('path', collect($nodes)->pluck('path'))->delete();
        $this->subFiles()->whereNotIn('path', collect($nodes)->pluck('path'))->delete();
    }

    /**
     * Refresh all the data for this folder and all sub folders from stack, until a file or folder with the given hash is found.
     * @param $hash
     */
    public function refreshRecursiveUntilHashFound($hash)
    {
        $this->refresh();

        if (StackFolder::where('path_hash', $hash)->first() !== null || StackFile::where('path_hash', $hash)->first() !== null ) {
            return;
        }

        foreach ($this->subFolders as $subFolder) {
            $subFolder->refreshRecursiveUntilHashFound($hash);
        }
    }
}
