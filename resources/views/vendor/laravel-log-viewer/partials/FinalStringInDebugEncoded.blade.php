@if(is_string($finalstring))
    @php($finalstring=str_ireplace('d-none','',$finalstring))
    @php($finalstring=str_ireplace('<div','<hr><div',$finalstring))
    @php($replaced=str_ireplace('class="','class="active show ',$finalstring))
    {!! $replaced !!}

@endif
