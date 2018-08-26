@extends('layouts.app')
@section('title', $item->path)
@section('type', 'mime_types:httpd/unix-directory')
@section('icon', $item->file_thumbnail)
@section('description', "Browse " . $item->name . " at " . config('app.name') . ".")

@section('content-center')
    <div class="table-responsive">
        <table class="table table-striped table-hover folder-table">

            <thead>
                <tr>
                    <th class="preview-column"></th>
                    <th>Name</th>
                    <th>Size</th>
                    <th class="icons-column"></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($item->subFolders()->orderBy('path')->get() as $subFolder)
                    <tr>
                        <td>
                            <img class="thumbnail preview-thumbnail" src="{{ $subFolder->file_thumbnail }}" alt="">
                        </td>
                        <td>
                            <a class="item-name item-center-vertical" href="{{ $subFolder->url_hash }}">{{ $subFolder->name }}</a>
                        </td>
                        <td>
                            <span class="item-center-vertical">{{ $subFolder->human_size }}</span>
                        </td>

                        <td>
                            <img tabindex="0" class="clpbrd ssw-share-icon" src="{{ '/media/icons/copy.svg' }}" alt="copy" data-clipboard-text="{{ $subFolder->url_hash }}" data-title="Copy Preview Link">
                            <img class="ssw-share-icon invisible" src="{{ '/media/icons/link.svg' }}" alt="">
                            <img class="ssw-share-icon invisible" src="{{ '/media/icons/download.svg' }}" alt="">
                        </td>
                    </tr>
                @endforeach

                @foreach ($item->subFiles()->orderBy('path')->get() as $subFile)
                    <tr>
                        <td>
                            <img class="thumbnail preview-thumbnail" src="{{ $subFile->file_thumbnail }}" alt="">
                        </td>
                        <td>
                            <a class="item-name item-center-vertical" href="{{ $subFile->url_hash }}">{{ $subFile->name }}</a>
                        </td>
                        <td>
                            <span class="item-center-vertical">{{ $subFile->human_size }}</span>
                        </td>

                        <td>
                            <img tabindex="0" class="clpbrd ssw-share-icon" src="{{ '/media/icons/copy.svg' }}" alt="copy" data-clipboard-text="{{ $subFile->url_hash }}" data-title="Copy Preview Link">
                            <img tabindex="0" class="clpbrd ssw-share-icon" src="{{ '/media/icons/link.svg' }}" alt="share" data-clipboard-text="{{ $subFile->url_full }}" data-title="Copy Media Link">
                            <img tabindex="0" class="clpbrd ssw-share-icon" src="{{ '/media/icons/download.svg' }}" alt="download" data-clipboard-text="{{ $subFile->url_download }}" data-title="Copy Download Link">
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
