@extends('layouts.app')
@section('title', $item->path)

@section('content-center')
    @if ($item->type === 'image')
        <img class="preview-image" src="{{ $item->preview_full }}" alt="{{ $item->name }}">

    @elseif ($item->type === 'video')
        <video class="preview-video" src="{{ $item->preview_full }}" controls autoplay></video>

    @elseif ($item->type === 'audio')
        <audio class="preview-audio" src="{{ $item->preview_full }}" controls autoplay></audio>

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

    @elseif ($item->type === 'package' || $item->type === 'executable')
        <p>No Preview Available</p>

    @else
        {? $content = \App\Stack\Downloaders::downloadPage($item->preview_full) ?}
        @if ($item->type === 'text' || !empty(trim(htmlentities($content))))
            <pre class="preview-text">{{ $content }}</pre>
        @else
            <p>No Preview Available</p>
        @endif

    @endif
@endsection

@section('content-left')
    <dl>
        <dt>File Name</dt>
        <dd>{{ $item->name }}</dd>
        <dt>Mime Type</dt>
        <dd>{{ $item->mimetype }}</dd>
        @if (isset($item->size))
            <dt>File Size</dt>
            <dd>{{ humanFileSize($item->size) }}</dd>
        @endif
    </dl>
@endsection

@section('content-right')
    <table class="table">
        <tbody>
            <tr>
                <td>
                    <a href="{{ url("/file/{$item->path_hash}") }}" title="Permalink">
                        <img class="share-icon center-block" src="{{ url('/media/icons/link.svg') }}" alt="link">
                    </a>
                </td>
                <td>
                    <a href="{{ url("/file/{$item->path_hash}?full=1") }}" title="Share">
                        <img class="share-icon center-block" src="{{ url('/media/icons/link.svg') }}" alt="link">
                    </a>
                </td>
                <td>
                    <a href="{{ url("/file/{$item->path_hash}?dl=1") }}" title="Download">
                        <img class="share-icon center-block" src="{{ url('/media/icons/download.svg') }}" alt="download">
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
