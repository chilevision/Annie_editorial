<div class="accordion col" id="sso-box">
    <div class="form-group">
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="sso" name="sso" wire:model="sso" data-toggle="collapse" data-target="#sso-settings" aria-expanded="false" aria-controls="sso-settings">
        <label class="form-check-label" for="sso">{{ __('settings.enable-sso') }}</label>
        </div>
    </div>
    <form wire:submit.prevent="saveSso">
        <div id="sso-settings" class="collapse @if($sso){{ 'show' }}@endif" aria-labelledby="headingOne" data-parent="#sso-box">
            <div class="card-body">
                <div class="row">
                    <x-Forms.input type="text" name="sso_hostname" wrapClass="col" wire="sso_host" label="{{ __('settings.sso-host') }}" inputClass="form-control" />
                    <x-Forms.input type="text" name="sso_validation" wrapClass="col" wire="sso_validation" label="{{ __('settings.sso-validation') }}" inputClass="form-control" />
                </div>
                <div class="row">
                    <x-Forms.input type="text" name="sso_version" wrapClass="col" wire="sso_version" label="{{ __('settings.sso-version') }}" inputClass="form-control" />
                    <x-Forms.input type="text" name="sso_logout" wrapClass="col" wire="sso_logout" label="{{ __('settings.sso-logout') }}" inputClass="form-control" />
                </div>
            </div>
        </div>
        <div class="form-row">
            <x-Forms.select name="ttl" wrapClass="col-auto" selectClass="form-control" wire="ttl" label="Remove inactive users after:" :options="$ttlOptions" />
        </div>
        <x-Forms.input type="submit" name="submit" wrapClass="form-row" label="{{ __('settings.submit') }}" inputClass="btn-dark btn-sm mt-4" />
    </form>
    
</div>
