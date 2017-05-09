<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StackFile extends Model
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
     * @return mixed|string
     */
    public function getPathDisplayAttribute() {
        return empty($this->path) ? '/' : $this->path;
    }

    /**
     * @return array
     */
    public function getBreadcrumbsAttribute() {
        $breadcrumbs = [];

        if (!empty($this->path)) {
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
    public function setNameAttribute($value) {
        if (empty($this->attributes['name'])) {
            $this->attributes['name'] = $value;
        }

        else {
            throw new \Exception('Value can only be assigned once.');
        }
    }

    /**
     * @param $value
     * @throws \Exception
     */
    public function setPathAttribute($value) {
        if (empty($this->attributes['path'])) {
            $this->attributes['path'] = $value;
            $this->attributes['path_hash'] = hashify($this->path_display);

            $this->attributes['path_slug'] = implode('/', collect(explode('/', $this->path_display))->map(function ($item, $key) {
                return slugify($item);
            })->all());
        }

        else {
            throw new \Exception('Value can only be assigned once.');
        }
    }
}
