@extends('layouts.app')
@section('title', $item->path)

@section('content-center')
    @if ($item->type === 'image')
        <img class="preview-image" src="{{ $item->preview_full }}" alt="{{ $item->name }}">

    @elseif ($item->type === 'video')
        <video class="preview-video" src="{{ $item->preview_full }}" controls autoplay>

    @elseif ($item->type === 'audio')
        <audio class="preview-audio" src="{{ $item->preview_full }}" controls autoplay>

    @elseif ($item->type === 'text')
        <pre class="preview-file">{{ \App\Stack\Downloader::downloadPage($item->preview_full) }}</pre>

    @else
        <p>No Preview Available</p>

    @endif
@endsection
