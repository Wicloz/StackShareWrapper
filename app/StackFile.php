<?php

namespace App;

use App\Stack\Downloaders;

/**
 * App\StackFile
 *
 * @property int $id
 * @property string $path
 * @property string $path_hash
 * @property int $size
 * @property string $mimetype_remote
 * @property int $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read array $parents
 * @property-read string $file_full
 * @property-read string $file_thumbnail
 * @property-read string $mimetype
 * @property-read string $name
 * @property-read string $path_clean
 * @property-read string $type
 * @property-read \App\StackFolder $parent
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereMimetypeRemote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePathHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile wherePathSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StackFile whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $extension
 * @property-read string $human_size
 * @property-read string $url_hash
 * @property-read string $mimeclean
 * @property-read string $url_download
 * @property-read string $url_full
 */
class StackFile extends StackItem
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'size', 'mimetype_remote', 'parent'];

    /**
     * Extensions for files that can be rendered as markdown.
     *
     * @var array
     */
    protected $markdownExtensions = [
        'md',
    ];

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
     * Mime types for executable files.
     *
     * @var array
     */
    protected $executableMimetypes = [
        'application/x-msdownload',
        'application/x-ms-dos-executable',
    ];

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
    public function getFileThumbnailAttribute()
    {
        switch ($this->type) {
            case 'image':
                $baseurl = config('stack.baseurl');
                $shareid = config('stack.shareid');
                return "{$baseurl}/public-share/{$shareid}/preview?path={$this->path}&mode=thumbnail";

            default:
                if (file_exists(public_path("/media/thumbnails/{$this->type}.svg"))) {
                    return "/media/thumbnails/{$this->type}.svg";
                } else {
                    return "/media/thumbnails/file.svg";
                }

        }
    }

    /**
     * @return string
     */
    public function getFileFullAttribute()
    {
        $baseurl = config('stack.baseurl');
        $shareid = config('stack.shareid');
        return "{$baseurl}/public-share/{$shareid}/preview?path={$this->path}&mode=full";
    }

    /**
     * @return string
     */
    public function getUrlFullAttribute()
    {
        $slugName = slugify($this->name);
        return secure_url("/file/{$this->path_hash}/{$slugName}");
    }

    /**
     * @return string
     */
    public function getUrlDownloadAttribute()
    {
        $slugName = slugify($this->name);
        return secure_url("/file/{$this->path_hash}/{$slugName}?dl=1");
    }

    /**
     * @return string
     */
    public function getMimetypeAttribute()
    {
        if (!empty($this->mimetype_remote) && $this->mimetype_remote !== 'application/octet-stream') {
            return $this->mimetype_remote;
        } else {
            return extensionToMimeType($this->extension);
        }
    }

    /**
     * @return string
     */
    public function getMimecleanAttribute()
    {
        return explode(';', $this->mimetype)[0];
    }

    /**
     * @return string
     */
    public function getTypeAttribute()
    {
        $mimeBits = explode('/', $this->mimeclean);

        // Markdown by extension
        if (in_array($this->extension, $this->markdownExtensions)) {
            return 'markdown';
        }

        // Code by extension or mimetype
        elseif (in_array($this->extension, $this->codeExtensions) || in_array($this->mimeclean, $this->codeMimetypes)) {
            return 'code';
        }

        // Minor types by mimetype
        elseif ($this->mimeclean === 'application/pdf') {
            return 'pdf';
        }
        elseif ($this->mimeclean === 'application/epub+zip') {
            return 'epub';
        }
        elseif ($this->mimeclean === 'application/json') {
            return 'json';
        }
        elseif ($this->mimeclean === 'text/xml') {
            return 'xml';
        }

        // Compressed files by mimetype
        elseif (in_array($this->mimeclean, $this->packageMimetypes)) {
            return 'package';
        }

        // Executables by mimetype
        elseif (in_array($this->mimeclean, $this->executableMimetypes)) {
            return 'executable';
        }

        // Default from mimetype
        elseif (!empty($mimeBits[0]) && $mimeBits[0] !== 'application') {
            return $mimeBits[0];
        }
        elseif (!empty($mimeBits[1]) && $mimeBits[1] !== 'octet-stream') {
            return $mimeBits[1];
        }
        // Default from extension
        elseif (!empty($this->extension)) {
            return $this->extension;
        }
        // Fallback
        else {
            return 'file';
        }
    }
}
