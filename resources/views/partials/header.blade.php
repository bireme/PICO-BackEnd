<header id="header">
    <div id="lang">
        @php
            $currentlang = Config::get('app.locale');
            $langs = Config::get('languages');
            $lastlang = array_keys($langs)[count($langs)-1];
        @endphp

        @foreach ($langs as $lang => $language)
            @if($currentlang==$lang)
                @continue
            @endif
            <button class="langbut" id="page-lang{{ $loop->index }}" name="{{ $lang }}">
                {{ $language }}
            </button>
            @if($loop->index<(count($langs)-1))
                @if(!($currentlang===$lastlang && $loop->index===(count($langs)-2)))
                    <label> | </label>
                @endif
            @endif
        @endforeach
        <label> </label>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-2 offset-2" id="logo">
                <h2>
                    <a href="{{Request::url() }}">
                        <img src="img/BVS.svg" alt="{{  __('lang.description') }}" class="img-fluid">
                    </a>
                </h2>
            </div>
            <div class="col-md-6" id="pico">
                <h1>
                    <a href="{{Request::url() }}">
                        <img src="img/Picos.svg" alt="{{  __('lang.title') }}" class="img-fluid">
                    </a>
                </h1>
            </div>
        </div>
    </div>
</header>
