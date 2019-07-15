<a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
    <span class="fa fa-folder"></span> {{$folder}}
</a>
@if ($current_folder == $folder)
    <div class="list-group folder">
        @foreach($folder_files as $file)
            <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
               class="list-group-item @if ($current_file == $file) llv-active @endif">
                {{$file}}
            </a>
        @endforeach
    </div>
@endif
