<div id="uploadImageModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="modal-spinner" class="spinner-overlay text-center">
                    <div class="overlay-text">
                        <span class="fa fa-spinner fa-spin fa-2x"></span>
                    </div>
                </div>

                <div>
                    <div id="demo-basic">
                    </div>
                    <a class="btn btn-outline-purple vanilla-rotate" data-deg="90"><i class="fas fa-undo"></i> {{__('Rotate Left')}}</a>
                    <a class="btn btn-outline-purple vanilla-rotate float-right" data-deg="-90"><i class="fas fa-undo fa-flip-horizontal"></i> {{__('Rotate Right')}}</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('Close') }}</button>
                <button id="submit-btn" type="button" class="btn btn-yellow">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</div>





