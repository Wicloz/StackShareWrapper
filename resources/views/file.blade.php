@extends('layouts.app')
@section('title', $item->path)

@section('content-center')
    <p>{{ $item->mimetype }}</p>

    @if ($item->type === 'image')
        <img class="preview-image" src="{{ $item->preview_full }}" alt="{{ $item->name }}">

    @elseif ($item->type === 'video')
        <video class="preview-video" src="{{ $item->preview_full }}" controls autoplay></video>

    @elseif ($item->type === 'audio')
        <audio class="preview-audio" src="{{ $item->preview_full }}" controls autoplay></audio>

    @elseif ($item->type === 'markdown')
        {{-- TODO --}}

    @elseif ($item->type === 'json')
        {{-- TODO --}}

    @elseif ($item->type === 'code')
        {{-- TODO --}}

    @else
        {? $content = \App\Stack\Downloaders::downloadPage($item->preview_full) ?}
        @if ($item->type === 'text' || !empty(trim(htmlentities($content))))
            <pre class="preview-file">{{ $content }}</pre>
        @else
            <p>No Preview Available</p>
        @endif

    @endif
@endsection
