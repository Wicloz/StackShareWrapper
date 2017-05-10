@extends('layouts.app')
@section('title', $item->path)

@section('content-center')
    <div class="table-responsive">
        <table class="table table-striped table-hover">

            <thead>
                <tr>
                    <th class="preview-column"></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($item->subFolders()->orderBy('path')->get() as $subFolder)
                    <tr class="folder-table-row">
                        <td>
                            <img class="thumbnail preview-thumbnail" src="{{ $subFolder->preview_thumb }}" alt="">
                        </td>
                        <td class="item-name-td">
                            <a href="{{ url($subFolder->path_slug) }}">{{ $subFolder->name }}</a>
                        </td>
                    </tr>
                @endforeach

                @foreach ($item->subFiles()->orderBy('path')->get() as $subFile)
                    <tr class="folder-table-row">
                        <td>
                            <img class="thumbnail preview-thumbnail" src="{{ $subFile->preview_thumb }}" alt="">
                        </td>
                        <td class="item-name-td">
                            <a href="{{ url($subFile->path_slug) }}">{{ $subFile->name }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
