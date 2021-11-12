<div>
    <div class="col align-self-end ml-auto">
        <button type="button" class="btn btn-custom float-right mb-4" data-toggle="modal" data-target="#userModal">{{ __('settings.create-user') }}</button>
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
                    <form name="delete-user-form" onSubmit="if(!confirm('{{ __('settings.message_warning1') }}{{ $user->name }}')){return false;}" method="POST" action="users/{{ $user->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="btn-group btn-group float-right">
                            <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#userModal" wire:click="editUser('{{ $user->id }}')"><i class="bi bi-pencil"></i></button>
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
