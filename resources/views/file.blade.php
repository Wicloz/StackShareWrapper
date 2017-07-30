@extends('layouts.app')
@section('title', $item->path)
@section('type', 'mime_types:' . $item->mimeclean)
@section('icon', $item->file_thumbnail)
@section('description', "View " . $item->name . " at " . config('app.name') . ".")

@section('head')
    @if ($item->type === 'audio')
        <meta property="og:audio" content="{{ $item->file_full }}">
    @elseif ($item->type === 'video')
        <meta property="og:video" content="{{ $item->file_full }}">
    @endif
@endsection

@section('content-center')
    @if ($item->type === 'image')
        <img class="preview-image" src="{{ $item->file_full }}" alt="{{ $item->name }}">

    @elseif ($item->type === 'video')
        <video class="preview-video" src="{{ $item->file_full }}" controls autoplay></video>

    @elseif ($item->type === 'audio')
        <audio class="preview-audio" src="{{ $item->file_full }}" controls autoplay></audio>

    @elseif ($item->type === 'markdown')
        {{-- TODO --}}

    @elseif ($item->type === 'code')
        {{-- TODO --}}

    @elseif ($item->type === 'pdf')
        {{-- TODO --}}

    @elseif ($item->type === 'epub')
        {{-- TODO --}}

    @elseif ($item->type === 'json')
        {{-- TODO --}}

    @elseif ($item->type === 'xml')
        {{-- TODO --}}

    @elseif ($item->type === 'package' || $item->type === 'executable')
        <p>No Preview Available</p>

    @else
        {? $content = \App\Stack\Downloaders::downloadPage($item->file_full) ?}
        @if ($item->type === 'text' || !empty(trim(htmlentities($content))))
            <pre class="preview-text">{{ $content }}</pre>
        @else
            <p>No Preview Available</p>
        @endif

    @endif
@endsection

@section('content-left')
    <dl class="preview-description">
        <dt>File Name</dt>
        <dd>{{ $item->name }}</dd>
        <dt>Mime Type</dt>
        <dd>{{ $item->mimetype }}</dd>
        <dt>Detected Type</dt>
        <dd>{{ $item->type }}</dd>
        @if (isset($item->size))
            <dt>File Size</dt>
            <dd>{{ $item->human_size }}</dd>
        @endif
    </dl>
@endsection

@section('content-right')
    <table class="table">
        <tbody>
            <tr>
                <td>
                    <img tabindex="0" class="clpbrd share-icon center-block" src="{{ '/media/icons/copy.svg' }}" alt="copy" data-clipboard-text="{{ $item->url_hash }}" data-title="Copy Preview Link">
                </td>
                <td>
                    <img tabindex="0" class="clpbrd share-icon center-block" src="{{ '/media/icons/link.svg' }}" alt="share" data-clipboard-text="{{ $item->url_full }}" data-title="Copy Media Link">
                </td>
                <td>
                    <img tabindex="0" class="clpbrd share-icon center-block" src="{{ '/media/icons/download.svg' }}" alt="download" data-clipboard-text="{{ $item->url_download }}" data-title="Copy Download Link">
                </td>
            </tr>
        </tbody>
    </table>
@endsection
