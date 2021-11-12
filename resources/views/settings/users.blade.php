@extends('layouts.app')
@section('content')
<div class="container light-style flex-grow-1 container-p-y">
	<div class="card">
		<div class="card-header">
			{{ __('app.users') }}
		</div>
        <div class="card-body">
            <div class="row pb-4">
                <livewire:sso />
            </div>
        </div>
        <livewire:users />
    </div>
    <!-- Modal -->
    <div class="modal fade" id="userModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <livewire:usermodal />
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')
<script>
    Livewire.on('refresh_users', data => {
    $('#userModal').modal('hide');
});
    Livewire.on('settings_saved', data => {
        $('#sso-box').addClass('saved');
        setTimeout(function () { 
            $('#sso-box').removeClass('saved');
        }, 2000);
    });
</script>
@endsection