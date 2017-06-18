@extends('layouts.app')
@section('title', $item->path)
@section('description', "View " . $item->name . " at " . config('app.name') . ".")
@section('head')
    <meta property="og:image" content="{{ $item->file_thumbnail }}">
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
                    <a href="{{ $item->url_hash }}" title="Link">
                        <img class="share-icon center-block" src="{{ url('/media/icons/copy.svg') }}" alt="link">
                    </a>
                </td>
                <td>
                    <a href="{{ "{$item->url_hash}?full=1" }}" title="Share">
                        <img class="share-icon center-block" src="{{ url('/media/icons/link.svg') }}" alt="link">
                    </a>
                </td>
                <td>
                    <a href="{{ "{$item->url_hash}?dl=1" }}" title="Download">
                        <img class="share-icon center-block" src="{{ url('/media/icons/download.svg') }}" alt="download">
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
