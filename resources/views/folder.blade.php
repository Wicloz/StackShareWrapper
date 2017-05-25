@extends('layouts.app')
@section('title', $item->path)

@section('content-center')
    <stack-item-list></stack-item-list>
@endsection

@section('head')
    <script>
        window.folders = '{!! json_encode($item->subFolders()->orderBy('path')->get()) !!}';
        window.files = '{!! json_encode($item->subFiles()->orderBy('path')->get()) !!}';
    </script>
@endsection
