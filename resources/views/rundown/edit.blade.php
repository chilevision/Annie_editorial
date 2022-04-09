@extends('layouts.app')
@section('add_scripts')
	<script src="{{ asset('js/pusher.min.js') }}"></script>
	<script>
		var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
			cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
		});
		var channel = pusher.subscribe('{{ $pusher_channel }}');
		channel.bind('{{ $rundown->id }}', function(data) {
			console.log(data.message);
			switch (data.message.type){
				case 'render' 			: 
					Livewire.emit('render');
					if (data.message.title != undefined) setNewTitle(data.message.title);
					break;
				case 'lockSorting'		: disable_sorting(data.message.code);	break;
				case 'unlockSorting'	: sortable.options.disabled = false;	break;
				case 'row_updated'		: 
					enable_menu(data.message.id);
					Livewire.emit('render');
					break;
				case 'prompter'			: break;
				default 				: lock(data.message); break;
			}
		});
		function setNewTitle(title){
			$('#navTitle').text(title);
		}
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
			<a href="/dashboard/rundown">{{ __('rundown.scripts') }}</a><i class="bi bi-caret-right"></i>{{ __('rundown.edit') }}: <cite id="navTitle" title="Source Title"> {{ $rundown->title }} </cite>
		</div>	
		<div class="card-body">
			@livewire('rundownrow-form', ['rundown' => $rundown])
			@livewire('rundown', ['rundown' => $rundown])
		</div>
	</div>
</div>
<!-- Modals -->
<div class="modal fade" id="casparModal" tabindex="-1" aria-labelledby="casparModalLabel" aria-hidden="true">
	<livewire:caspar />
</div>

<x-Bootstrap.modal id="textEditorModal" size="xl" saveBtn="{{ __('settings.submit')}}">
	<div id="summernote"></div>
</x-Bootstrap.modal>

<x-Bootstrap.modal id="gfxDataModal">
	<h1>Hej</h1>
</x-Bootstrap.modal>
<!-- /Modals -->
@endsection
@section('footer_scripts')
<script src="{{ asset('js/rundown.js') }}"></script>
@if (!$rundown->sortable)
	<script> $( document ).ready(function() { sortable.options.disabled = true; }); </script>
@endif
@endsection