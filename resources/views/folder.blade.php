@extends('layouts.app')
@section('title', $folder->path)

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
                @foreach ($folder->subFolders as $subFolder)
                    <tr>
                        <td></td>
                        <td>
                            <a href="{{ url($subFolder->path) }}">{{ $subFolder->name }}</a>
                        </td>
                    </tr>
                @endforeach

                @foreach ($folder->subFiles as $subFile)
                    <tr>
                        <td></td>
                        <td>
                            <a href="{{ url($subFile->path) }}">{{ $subFile->name }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
