@extends('vendor.laravel-log-viewer.layout.log-layout')
@section('contentone')
    @foreach($folders as $folder)
        <div class="list-group-item">
            @include('vendor.laravel-log-viewer.partials.channelpresentation',['current_folder'=>$current_folder,'folder'=>$folder,'folder_files'=>$folder_files])
        </div>
    @endforeach
    @foreach($files as $file)
        @include('vendor.laravel-log-viewer.partials.filepresentation',['file'=>$file,'current_file'=>$current_file])
    @endforeach
@endsection

@section('contenttwo')
    @if ($logs === null)
        <div>
            Log file >50M, please download it.
        </div>
    @else
        <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
            <thead>
            <tr>
                <th style="width: 9%">Level</th>
                {{--<th>Context</th>--}}
                <th style="width: 15%">Date</th>
                <th style="width: 76%">Content</th>
            </tr>
            </thead>
            <tbody>
            @foreach($logs as $key => $log)
                <tr data-display="stack{{{$key}}}">
                    <td class="nowrap text-{{{$log['level_class']}}}">
                        <span class="fa fa-{{{$log['level_img']}}}"
                              aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                    </td>
                    <td class="date">{{{$log['date']}}}</td>
                    <td class="text">
                        <button type="button"
                                class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2 expandinfo"
                                data-display="stack{{{$key}}}">
                            <span class="fa fa-search"></span>
                        </button>
                        {{ $log['text'] }}
                        <div class="container-fluid logcontent" id="stack{{{$key}}}"
                             style="display: none; white-space: pre-wrap;">
                            <div class="row" style="padding-top:15px;padding-bottom:15px;">
                                @include('vendor.laravel-log-viewer.partials.logpresentation',['key'=>$key,'logstack'=>$log['stack']])
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
@section('contentthree')
    @if($current_file)
        <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-download"></span> Download file
        </a>
        -
        <a id="clean-log"
           href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-sync"></span> Clean file
        </a>
        -
        <a id="delete-log"
           href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-trash"></span> Delete file
        </a>
        @if(count($files) > 1)
            -
            <a id="delete-all-log"
               href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                <span class="fa fa-trash-alt"></span> Delete all files
            </a>
        @endif
    @endif
@endsection
