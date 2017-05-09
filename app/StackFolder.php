<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Stack\Downloader;

class StackFolder extends Model
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

    /**
     * Refresh all the data for this folder from stack.
     */
    public function refresh()
    {
        $json = Downloader::downloadList($this->path);
        $remoteNames = collect([]);

        foreach ($json->nodes as $node) {
            $name = str_replace_first($this->path_display, '', $node->path);
            if (str_starts_with($name, '/')) {
                $name = str_replace_first('/', '', $name);
            }
            $remoteNames[] = $name;

            if ($node->mimetype === 'httpd/unix-directory') {
                $this->subFolders()->updateOrCreate([
                    'name' => $name,
                    'path' => $this->path . '/' . $name,
                ],[
                ]);
            }

            else {
                $this->subFiles()->updateOrCreate([
                    'name' => $name,
                    'path' => $this->path . '/' . $name,
                ],[
                    'mimetype' => $node->mimetype,
                ]);
            }
        }

        StackFolder::whereIn('name',  $this->subFolders()->pluck('name')->diff($remoteNames))->delete();
        StackFile::whereIn('name',  $this->subFiles()->pluck('name')->diff($remoteNames))->delete();
    }
}
