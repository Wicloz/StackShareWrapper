@extends('layouts.app')
@section('title', $item->path)

@section('content-center')
    <div class="table-responsive">
        <table class="table table-striped table-hover folder-table">

            <thead>
                <tr>
                    <th class="preview-column"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($item->subFolders()->orderBy('path')->get() as $subFolder)
                    <tr>
                        <td>
                            <img class="thumbnail preview-thumbnail" src="{{ $subFolder->preview_thumb }}" alt="">
                        </td>
                        <td>
                            <a class="item-name" href="{{ url($subFolder->path_slug) }}">{{ $subFolder->name }}</a>
                        </td>

                        <td>
                            <a href="{{ url('/folder/' . $subFolder->path_hash) }}" title="Permalink">
                                <img class="action-icon" src="{{ url('/media/icons/link.svg') }}" alt="link">
                            </a>
                        </td>
                        <td></td>
                    </tr>
                @endforeach

                @foreach ($item->subFiles()->orderBy('path')->get() as $subFile)
                    <tr>
                        <td>
                            <img class="thumbnail preview-thumbnail" src="{{ $subFile->preview_thumb }}" alt="">
                        </td>
                        <td>
                            <a class="item-name" href="{{ url($subFile->path_slug) }}">{{ $subFile->name }}</a>
                        </td>

                        <td>
                            <a href="{{ url('/file/' . $subFile->path_hash) }}" title="Permalink">
                                <img class="action-icon" src="{{ url('/media/icons/link.svg') }}" alt="link">
                            </a>
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
