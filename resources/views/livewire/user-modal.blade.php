
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{ __( $header ) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form wire:submit.prevent="{{ $form_action }}" method="POST">
        <div class="modal-body">
            <x-Forms.input type="text" name="name" value="" wrapClass="col" wire="name" label="{{ __('settings.name') }}" inputClass="form-control" />
            @error('name') <span class="error">{{ $message }}</span> @enderror
            <x-Forms.input type="text" name="email" value="" wrapClass="col" wire="email" label="{{ __('settings.email') }}" inputClass="form-control" />
            @error('email') <span class="error">{{ $message }}</span> @enderror
            <x-Forms.input type="password" name="password" value="" wrapClass="col" wire="password" label="{{ __('settings.password') }}" inputClass="form-control" />
            <x-Forms.input type="password" name="password_confirmation" value="" wrapClass="col" wire="password_confirmation" label="{{ __('settings.password-confirm') }}" inputClass="form-control" />
            @error('password') <span class="error">{{ $message }}</span> @enderror
            <x-Forms.input type="checkbox" name="admin" value="" wrapClass="col" wire="admin" label="{{ __('settings.admin') }}" inputClass="form-control" />
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="resetModal" data-dismiss="modal">{{ __('settings.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ __( $submit_btn ) }}</button>
        </div>
    </form>
</div>
