<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StackItem
 *
 * @property-read array $breadcrumbs
 * @property-read string $name
 * @property-read string $path_clean
 * @property-write mixed $path
 * @mixin \Eloquent
 */
class StackItem extends Model
{
    /**
     * @return string
     */
    public function getPathCleanAttribute() {
        return $this->path === '/' ? '' : $this->path;
    }

    /**
     * @return string
     */
    public function getNameAttribute() {
        $names = explode('/', $this->path);
        return $names[count($names) - 1];
    }

    /**
     * @return array
     */
    public function getBreadcrumbsAttribute() {
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
     * @param $value
     * @throws \Exception
     */
    public function setPathAttribute($value) {
        if (empty($this->attributes['path'])) {
            $this->attributes['path'] = $value;
            $this->attributes['path_hash'] = hashify($value);

            $this->attributes['path_slug'] = implode('/', collect(explode('/', $value))->map(function ($item) {
                return slugify($item);
            })->all());
        }

        else {
            throw new \Exception('Value \'path\' can only be assigned once.');
        }
    }
}
