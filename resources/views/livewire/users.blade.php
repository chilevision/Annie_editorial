<div class="container light-style flex-grow-1 container-p-y">
	<div class="card">
		<div class="card-header">
			{{ __('app.users') }}
		</div>
        <div class="card-body">
            <div class="row pb-4">
            <div class="accordion col" id="sso-box">
                <div class="form-group">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="sso" name="sso" wire:model="sso" data-toggle="collapse" data-target="#sso-settings" aria-expanded="false" aria-controls="sso-settings">
                    <label class="form-check-label" for="sso">{{ __('settings.enable-sso') }}</label>
                    </div>
                </div>
                <div id="sso-settings" class="collapse @if($sso){{ 'show' }}@endif" aria-labelledby="headingOne" data-parent="#sso-box">
                    <div class="card-body">
                        <form wire:submit.prevent="saveSso">
                            <x-Forms.input type="text" name="sso_hostname" value="" wrapClass="col" wire="sso_host" label="{{ __('settings.sso-host') }}" inputClass="form-control" />
                            <x-Forms.input type="text" name="sso_validation" value="" wrapClass="col" wire="sso_validation" label="{{ __('settings.sso-validation') }}" inputClass="form-control" />
                            <x-Forms.input type="text" name="sso_version" value="" wrapClass="col" wire="sso_version" label="{{ __('settings.sso-version') }}" inputClass="form-control" />
                            <x-Forms.input type="text" name="sso_logout" value="" wrapClass="col" wire="sso_logout" label="{{ __('settings.sso-logout') }}" inputClass="form-control" />
                            <x-Forms.input type="submit" name="submit" value="" wrapClass="" wire="" label="{{ __('settings.submit') }}" inputClass="btn-dark btn-sm mt-4" />
                        </form>
                    </div>
                </div>
            </div>
            <div class="col align-self-end ml-auto">
                <button type="button" class="btn btn-custom float-right" data-toggle="modal" data-target="#staticBackdrop">{{ __('settings.create-user') }}</button>
            </div>
            </div>
            <table class="table table-striped table-hover">
                <thead class="thead-custom">
                    <tr>
                        <th><a href="#" wire:click="changeOrder('id')" class="text-light">{{ __('settings.id') }}@if ($orderBy == 'id') {!! $arrow !!} @endif</a></th>
                        <th><a href="#" wire:click="changeOrder('name')" class="text-light">{{ __('settings.name') }}@if ($orderBy == 'name') {!! $arrow !!} @endif</a></th>
                        <th><a href="#" wire:click="changeOrder('email')" class="text-light">{{ __('settings.email') }}@if ($orderBy == 'email') {!! $arrow !!} @endif</a></th>
                        <th><a href="#" wire:click="changeOrder('created_at')" class="text-light">{{ __('settings.created') }}@if ($orderBy == 'created_at') {!! $arrow !!} @endif</a></th>
                        <th><a href="#" wire:click="changeOrder('admin')" class="text-light">{{ __('settings.admin') }}@if ($orderBy == 'admin') {!! $arrow !!} @endif</a></th>
                        <th>{{ __('rundown.manage') }}
                            <select wire:model="perPage" class="float-right">
        @foreach ( $per_page as $value )
                                <option value="{{ $value }}">{{ $value }}</option>
        @endforeach
                            </select>
                        </th>
                    </tr>
                </thead>
                <tbody>
            @foreach ($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ gmdate('Y-m-d', strtotime($user->created_at)) }}</td>
                        <td>{{ $user->admin }}</td>
                        <td width="150px">
                            <form name="delete-user-form" onsubmit="return confirm({{ __('settings.message_warning1') }});" method="POST" action="settings/{{ $user->id }}">
                                @csrf
                                @method('DELETE')
                                <div class="btn-group btn-group float-right">
                                    <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#staticBackdrop"><i class="bi bi-pencil"></i></button>
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                </div>
                            </form>
                        </td>
            @endforeach        
                    </tr>
                </tbody>
            </table>
            <p class="text-center text-white bg-dark"><i class="bi bi-pencil"></i> = {{ __('rundown.edit') }} <i class="bi bi-trash ml-4"></i> = {{ __('rundown.delete') }}</p>
            <div class="d-flex justify-content-center">{!! $users->links() !!}</div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <x-Forms.input type="text" name="name" value="" wrapClass="col" wire="" label="{{ __('settings.name') }}" inputClass="form-control" />
                    <x-Forms.input type="text" name="email" value="" wrapClass="col" wire="" label="{{ __('settings.email') }}" inputClass="form-control" />
                    <x-Forms.input type="password" name="password" value="" wrapClass="col" wire="" label="{{ __('settings.password') }}" inputClass="form-control" />
                    <x-Forms.input type="password" name="password_confrim" value="" wrapClass="col" wire="" label="{{ __('settings.password-confirm') }}" inputClass="form-control" />
                    <x-Forms.input type="checkbox" name="admin" value="" wrapClass="col" wire="" label="{{ __('settings.admin') }}" inputClass="form-control" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
