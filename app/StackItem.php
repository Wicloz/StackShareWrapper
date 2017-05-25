<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StackItem
 *
 * @property int $id
 * @property string $path
 * @property string $path_slug
 * @property string $path_hash
 * @property int $size
 * @property int $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read array $breadcrumbs
 * @property-read string $name
 * @property-read string $path_clean
 * @property-read string $extension
 * @mixin \Eloquent
 * @property-read string $human_size
 * @property-read string $url_hash
 * @property-read string $url_slug
 */
class StackItem extends Model
{
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
    public function getBreadcrumbsAttribute()
    {
        $breadcrumbs = [];

        if (!empty($this->path_clean)) {
            $names = array_slice(explode('/', $this->path), 1);
            $slugs = array_slice(explode('/', $this->path_slug), 1);

            foreach ($names as $index => $name) {
                $breadcrumbs[$name] = ($index > 0 ? $breadcrumbs[$names[$index - 1]] : '') . '/' . $slugs[$index];
            }
        }

        return $breadcrumbs;
    }

    /**
     * @return string
     */
    public function getExtensionAttribute()
    {
        $nameBits = explode('.', $this->name);
        return count($nameBits) > 1 ? $nameBits[count($nameBits) - 1] : '';
    }

    /**
     * @return string
     */
    public function getUrlSlugAttribute()
    {
        return url($this->path_slug);
    }

    /**
     * @return string
     */
    public function getUrlHashAttribute()
    {
        return url($this->path_hash);
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

            $this->attributes['path_slug'] = implode('/', collect(explode('/', $value))->map(function ($item) {
                return slugify($item);
            })->all());

            $this->attributes['path_hash'] = hashify($this->attributes['path_slug']) . (Static::class == StackFile::class ? '.' . mb_strtolower($this->extension) : '');
        }

        else {
            throw new \Exception('Value \'path\' can only be assigned once.');
        }
    }
}
