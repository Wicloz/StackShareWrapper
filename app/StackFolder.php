<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StackFolder extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent'];

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
}
