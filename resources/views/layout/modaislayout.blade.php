<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" id="close{{ $modalId }}" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            @yield('modal-body')

            <div class="modal-footer">
                @yield('modal-footer')
            </div>
        </div>

    </div>
</div>
