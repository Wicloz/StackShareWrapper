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
    protected $fillable = ['name', 'parent', 'mimetype'];

    /**
     * Get the parent folder for this file.
     */
    public function parent()
    {
        return $this->belongsTo('App\StackFolder');
    }

    /**
     * Returns the full path to this file.
     *
     * @return string
     */
    public function getPathAttribute()
    {
        $current = $this;
        $path = '';

        while ($current->name !== null) {
            $path = '/' . $current->name . $path;
            $current = $current->parent;
        }

        if ($path === '') {
            $path = '/';
        }

        return $path;
    }
}
