<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">{{ __( $header ) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form wire:submit.prevent="{{ $form_action }}" method="POST">
        <div class="modal-body">
            <input type="hidden" name="id" wire:model="userId" />
            <x-forms.input type="text" name="name" wrapClass="col" wire="name" label="{{ __('settings.name') }}" inputClass="form-control" />
            <x-forms.input type="text" name="email" wrapClass="col" wire="email" label="{{ __('settings.email') }}" inputClass="form-control" />
            <x-forms.input type="password" name="password" wrapClass="col" wire="password" label="{{ __('settings.password') }}" inputClass="form-control" />
            <x-forms.input type="password" name="password_confirmation" wrapClass="col" wire="password_confirmation" label="{{ __('settings.password-confirm') }}" inputClass="form-control" />
            <x-forms.input type="checkbox" name="admin" wrapClass="col" wire="admin" label="{{ __('settings.admin') }}" inputClass="form-control" />
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="resetModal" data-dismiss="modal">{{ __('settings.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ __( $submit_btn ) }}</button>
        </div>
    </form>
</div>
