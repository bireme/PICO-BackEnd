@spaceless
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content text-center">
            <div class="modal-title-div text-center">
                <h2>
                    @yield('modal-header')
                </h2>
            </div>
            <div class="modal-body text-center">
                <h6>
                    @yield('modal-body')
                </h6>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="offset-md-3 col-md-6 text-center">
                            <h4>
                                @yield('modal-footer')
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endspaceless
