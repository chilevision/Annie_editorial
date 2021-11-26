@extends('layouts.app')
@section('add_scripts')
	<script src="{{ asset('js/pusher.min.js') }}"></script>
	<script>
		var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
			cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
		});
		var channel = pusher.subscribe('{{ $pusher_channel }}');
		channel.bind('{{ $rundown->id }}', function(data) {
			console.log(data.message.type);
			switch (data.message.type){
				case 'render' 			: Livewire.emit('render'); 				break;
				case 'lockSorting'		: disable_sorting(data.message.code);	break;
				case 'unlockSorting'	: sortable.options.disabled = false;	break;
				case 'row_updated'		: 
					enable_menu(data.message.id);
					Livewire.emit('render');
					break;
				default : lock(data.message); break;
			}
		});
	</script>
	<script src="{{ asset('js/Sortable.min.js')}}"></script>
	<script src="{{ asset('js/summernote.min.js') }}"></script>
@stop
@section('add_styles')
	<link rel="stylesheet" href="{{ asset('css/summernote.min.css') }}" />
@stop
@section('content')
<div class="container-fluid">
	<div class="card" style="width: 1400px; overflow-x: scroll; margin: 0 auto;">
		<div class="card-header">
			<a href="/dashboard/rundown">{{ __('rundown.scripts') }}</a><i class="bi bi-caret-right"></i>{{ __('rundown.edit') }}: <cite title="Source Title"> {{ $rundown->title }} </cite>
		</div>	
		<div class="card-body">
			@livewire('rundownrow-form', ['rundown' => $rundown])
			<table class="table table-bordered table-sm mb-n1 mt-4">
				<tr>
					<td class="text-center">
						<a  href="/dashboard/rundown/{{ $rundown->id }}/editcal"><i class="bi bi-pencil float-right"></i></a>
						<h2>{{ $rundown->title }}</h2>
					</td>
				</tr>
			</table>
			<table class="table table-bordered table-sm mt-1">
				<thead>
					<tr>
					<th scope="col">{{ __('rundown.air_date') }}</th>
					<th scope="col">{{ __('rundown.air_time') }}</th>
					<th scope="col">{{ __('rundown.lenght') }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td>{{ gmdate('Y-m-d', strtotime($rundown->starttime))}}</td>
					<td>{{ date('H:i', strtotime($rundown->starttime)).' - '.date('H:i', strtotime($rundown->stoptime)) }}</td>
					<td>{{ gmdate('H:i', $rundown->duration) }}</td>
					</tr>						
				</tbody>
			</table>
			<div id="rundown-edit-table-wrap">
				@livewire('rundown', ['rundown' => $rundown])
			</div>
		</div>
	</div>
</div>
<!-- Modals -->
<div class="modal fade" id="casparModal" tabindex="-1" aria-labelledby="casparModalLabel" aria-hidden="true">
	<livewire:caspar />
</div>
<div class="modal fade" id="textEditorModal" tabindex="-1" aria-labelledby="textEditorModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="textEditorTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="summernote"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('rundown.cancel') }}</button>
				<button type="button" id="textEditorSave" class="btn btn-custom" onclick="">Save changes</button>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer_scripts')
<script src="{{ asset('js/rundown.js') }}"></script>
@if (!$rundown->sortable)
	<script> $( document ).ready(function() { sortable.options.disabled = true; }); </script>
@endif
@endsection