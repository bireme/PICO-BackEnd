<nav class="navbar navbar-default fixed-top text-center h6" style="background-color:#2d3e50;color:white;font-size:12px;padding:0;margin:0;" role="navigation">
    <div class="container-fluid">
        <div class="row" style ="height:auto;width:100%;">
            <div class="col-md-4" style="display:flex;justify-content:center;align-items: center;flex-flow: column;padding:0;margin:0;">
                <span>Log and error handling system based on rap2hpoutre/laravel-log-viewer and improved by Daniel Nieto (https://github.com/nietodaniel)</span>
            </div>
            <div class="col-md-4" style="display:flex;justify-content:center;align-items: center;flex-flow: column;padding:0;margin:0;">
                <span style="font-size:16px">User: {{ \PICOExplorer\Facades\AuthHandlerFacade::UserData() }}</span>
            </div>
            <div class="col-md-4" style="display:flex;justify-content:center;align-items: center;flex-flow: column;padding:0;margin:0;">
                @if(\PICOExplorer\Facades\AuthHandlerFacade::isAdmin()>0)
                    <a class="btn btn-primary btn-block" href="{{ URL::to('admin/logout') }}">Logout</a>
                    <a class="btn btn-primary btn-block" href="{{ URL::to('health/panel') }}">Components Health</a>
                @endif
            </div>
        </div>
    </div>
</nav>
