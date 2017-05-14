<?php

namespace App;

use App\Stack\Downloaders;

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
 * @property-read string $size
 * @property-read \App\StackFolder $parent
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePathHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePathSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $mimetype_remote
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereMimetypeRemote($value)
 */
class StackFile extends StackItem
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'parent', 'mimetype_remote'];

    /**
     * Extensions for files that can be previewed as code.
     *
     * @var array
     */
    protected $codeExtensions = [
        'sh',
        'bat',
        'php',
        'js',
        'cs',
        'cpp',
        'c++',
        'html',
        'css',
        'scss',
    ];

    /**
     * Mime types for files that can be previewed as code.
     *
     * @var array
     */
    protected $codeMimetypes = [
        'application/x-php',
        'application/x-javascript',
        'application/x-shellscript',
        'text/html',
        'text/css',
    ];

    /**
     * Mime types for compressed files.
     *
     * @var array
     */
    protected $packageMimetypes = [
        'application/zip',
        'application/x-gzip',
        'application/x-bzip',
        'application/x-xz',
        'application/x-tar',
        'application/x-ms-wim',
        'application/x-7z-compressed',
    ];

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
    public function getMimetypeAttribute()
    {
        if ($this->mimetype_remote !== 'application/octet-stream') {
            return $this->mimetype_remote;
        } else {
            return filenameToMimeType($this->name);
        }
    }

    /**
     * @return string
     */
    public function getTypeAttribute()
    {
        $nameBits = explode('.', $this->name);
        $mimeBits = explode('/', $this->mimetype);
        $mimeClean = (count($mimeBits) > 1) ? ($mimeBits[0] . '/' . $mimeBits[1]) : ($this->mimetype);

        // Markdown by extension
        if (count($nameBits) > 1 && $nameBits[count($nameBits) - 1] === 'md') { // TODO
            return 'markdown';
        }

        // Code by extension and mimetype
        elseif ((count($nameBits) > 1 && in_array($nameBits[count($nameBits) - 1], $this->codeExtensions)) || in_array($mimeClean, $this->codeMimetypes)) {
            return 'code';
        }

        // Minor types by mimetype
        elseif ($mimeClean === 'application/pdf') {
            return 'pdf';
        }
        elseif ($mimeClean === 'application/epub+zip') {
            return 'epub';
        }
        elseif ($mimeClean === 'application/json') {
            return 'json';
        }

        // Compressed files by mimetype
        elseif (in_array($mimeClean, $this->packageMimetypes)) {
            return 'package';
        }

        // Windows executables by mimetype
        elseif ($mimeClean === 'application/x-msdownload' || $mimeClean === 'application/x-ms-dos-executable') {
            return 'executable';
        }

        // Default from mimetype
        elseif (!empty($mimeBits[0])) {
            return $mimeBits[0];
        }

        // Default from extension
        elseif (count($nameBits) > 1) {
            return $nameBits[count($nameBits) - 1];
        }

        // Fallback
        else {
            return 'file';
        }
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
                if (file_exists(public_path("/media/thumbnails/{$this->type}.svg"))) {
                    return url("/media/thumbnails/{$this->type}.svg");
                } else {
                    return url("/media/thumbnails/file.svg");
                }

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

    /**
     * @return float|null
     */
    public function getSizeAttribute()
    {
        return Downloaders::getFileSize($this->download_remote);
    }
}
