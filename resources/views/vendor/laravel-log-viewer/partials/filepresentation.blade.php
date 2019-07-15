<a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
   class="list-group-item @if ($current_file == $file) llv-active @endif">
    {{$file}}
</a>
