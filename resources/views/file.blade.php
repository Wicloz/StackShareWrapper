@extends('layouts.app')
@section('title', $item->path)

@section('content-center')
    @if ($item->mime_min === 'image')
        <img src="{{ $item->preview_full }}" alt="{{ $item->name }}">
    @elseif ($item->mime_min === 'video')
        <video src="{{ $item->preview_full }}" autoplay>
    @elseif ($item->mime_min === 'audio')
        <audio src="{{ $item->preview_full }}" controls autoplay>
    @endif
@endsection
