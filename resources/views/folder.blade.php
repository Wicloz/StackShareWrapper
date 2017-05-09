@extends('layouts.app')
@section('title', $item->path_display)

@section('content-center')
    <div class="table-responsive">
        <table class="table table-striped table-hover">

            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($item->subFolders as $subFolder)
                    <tr>
                        <td></td>
                        <td>
                            <a href="{{ url($subFolder->path_slug) }}">{{ $subFolder->name }}</a>
                        </td>
                    </tr>
                @endforeach

                @foreach ($item->subFiles as $subFile)
                    <tr>
                        <td></td>
                        <td>
                            <a href="{{ url($subFile->path_slug) }}">{{ $subFile->name }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
