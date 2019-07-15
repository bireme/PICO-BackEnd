<meta charset="UTF-8">
<meta name="autor" content="{{ __('lang.autor') }}">
<meta name="keywords" content="{{ __('lang.keywords') }}">
<meta name="description" content="{{ __('lang.description') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('lang.title') }}</title>
@php
    $currentlang = Config::get('app.locale');
    $langs = Config::get('languages');
    $baseURL = url('/');
@endphp
@foreach ($langs as $lang => $language)
    @if($currentlang==$lang)
        @continue
    @endif
    <link rel="alternate" hreflang="{{ $lang }}" href="{{ $baseURL . '/' . $lang }}" />
@endforeach
<link rel="stylesheet" href="{{ mix('css/all.css') }}">

