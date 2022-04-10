<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id . 'Label' }}" aria-hidden="true">
    <div class="modal-dialog {{ $size ? 'modal-'.$size : ''}}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id . 'Label' }}">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('app.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                {{ $footer ? $footer : '' }}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('app.close') }}</button>
                @if (!$saveBtn == '')
                <button type="button" id="{{ $id.'Save' }}" class="btn btn-primary" {!! $saveClick ? 'onclick="'.$saveClick.'"' : '' !!}>{{ __($saveBtn) }}</button>
                @endif
            </div>
        </div>
    </div>
</div>