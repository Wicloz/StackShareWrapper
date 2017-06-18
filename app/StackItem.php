<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\StackItem
 *
 * @property int $id
 * @property string $path
 * @property string $path_hash
 * @property int $size
 * @property int $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read array $parents
 * @property-read string $name
 * @property-read string $path_clean
 * @mixin \Eloquent
 * @property-read string $human_size
 * @property-read string $url_hash
 * @property-read \App\StackFolder $parent
 */
class StackItem extends Model
{
    /**
     * Get the parent folder for this folder.
     */
    public function parent()
    {
        return $this->belongsTo('App\StackFolder');
    }

    /**
     * @return string
     */
    public function getPathCleanAttribute()
    {
        return $this->path === '/' ? '' : $this->path;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        $names = explode('/', $this->path);
        return $names[count($names) - 1];
    }

    /**
     * @return array
     */
    public function getParentsAttribute()
    {
        $parents = new Collection();

        $parent = $this;
        while ($parent->parent !== null) {
            $parents->prepend($parent);
            $parent = $parent->parent;
        }

        return $parents->all();
    }

    /**
     * @return string
     */
    public function getUrlHashAttribute()
    {
        return url((Static::class == StackFolder::class ? '/folder/' : '/file/') . $this->path_hash);
    }

    /**
     * @return string
     */
    public function getHumanSizeAttribute()
    {
        return humanFileSize($this->size);
    }

    /**
     * @param $value
     * @throws \Exception
     */
    public function setPathAttribute($value)
    {
        if (empty($this->attributes['path'])) {
            $this->attributes['path'] = $value;
            $this->attributes['path_hash'] = hashify(mb_strtolower($value)) . (Static::class == StackFile::class ? '.' . mb_strtolower($this->extension) : '');
        }

        else {
            throw new \Exception('Value \'path\' can only be assigned once.');
        }
    }
}
