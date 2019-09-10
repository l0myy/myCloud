@extends('layouts.app')
@if(Auth::check())

@section('info')

    @foreach($dirs as $dir)
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <ul class="list-unstyled">
                        <li>{{$dir}}
                        </li>
                    </ul>
                </div>
                <div class="col-sm">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu3"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                            <form action="{{route('delDir')}}" method="POST">
                                @csrf
                                <input type="hidden" value={{$dir}} name="dirName" class="form-control-file"
                                       id="exampleFormControlFile1">
                                <button type="submit" class="dropdown-item">Delete</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @endforeach
    <hr>
    @foreach($files as $file)
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <ul class="list-unstyled">
                        <li>{{$file}}
                        </li>
                    </ul>
                </div>
                <div class="col-sm">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <form action="{{route('link')}}" method="POST">
                                @csrf
                                <input type="hidden" value={{$file}} name="fileName" class="form-control-file"
                                       id="exampleFormControlFile1">
                                <button type="submit" class="dropdown-item">Download</button>
                            </form>
                            <form action="{{route('delFile')}}" method="POST">
                                @csrf
                                <input type="hidden" value={{$file}} name="fileName" class="form-control-file"
                                       id="exampleFormControlFile1">
                                <button type="submit" class="dropdown-item">Delete</button>
                            </form>

                            <button class="dropdown-item" data-toggle="modal" data-target=".move-file">Move/Rename
                            </button>

                        </div>
                    </div>
                </div>

                <div class="modal fade move-file" tabindex="-2" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="container">
                                <h2 style="text-align: center;">Choose file for move/rename</h2>
                                <form action="{{route('editFile')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <select name="oldFileName" class="form-control">
                                        @foreach($files as $file)
                                            <option>{{$file}}</option>
                                        @endforeach
                                    </select>
                                    <br>
                                    <h2 style="text-align: center;">Choose new file name/path</h2>
                                    <input name="newFileName" type="text" class="form-control"
                                           placeholder="Enter new file name or path for move">
                                    <br>
                                    <button type="submit" class="btn btn-outline-secondary">Confirm</button>
                                </form>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    @endforeach

    <br>
    <div class="d-flex">
        <div class="d-flex flex-row">
            <div class="row">
                <div class="col-2">
                    <button type="submit" class="btn btn-outline-secondary" data-toggle="modal" data-target=".add-file">
                        Add new file
                    </button>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row">
            <div class="col-2">
                <button type="submit" class="btn btn-outline-secondary" data-toggle="modal" data-target=".add-dir">
                    Create new
                    directory
                </button>
            </div>
        </div>
    </div>


    <div class="modal fade add-file" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <h2 style="text-align: center;">Choose new file</h2>
                <div class="container">
                    <form action="{{ route('load') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="newFile" class="form-control-file">
                        <br>
                        <button type="submit" class="btn btn-outline-secondary">Confirm</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade add-dir" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <h2 style="text-align: center;">Enter name for new directory</h2>
                <div class="container">
                    <form action="{{route('makeDir')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input name="dirName" type="text" class="form-control" placeholder="Enter dir name for create">
                        <br>
                        <button type="submit" class="btn btn-outline-secondary">Confirm</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
@endsection
@endif