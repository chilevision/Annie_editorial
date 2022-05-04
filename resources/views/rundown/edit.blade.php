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
				case 'render' 	:
					reload();
					if (data.message.title != undefined) setNewTitle(data.message.title);
					break;
				case 'lockSorting'		: disable_sorting(data.message.code);	break;
				case 'unlockSorting'	:
					reload();
					sortable.options.disabled = false;
					break;
				case 'row_updated'		:
					enable_menu(data.message.id);
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
			@livewire('rundown', ['rundown' => $rundown, 'colors' => $colors])
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

<x-Bootstrap.modal id="gfxDataModal" saveBtn="{{ __('settings.submit') }}" saveClick="moveGfxData();" title="{{ __('rundown.add_gfx') }}">
	<div>
        <h1><i id="toggle" class="bi bi-plus-square-fill"></i></h1>
        <input type="text" id="add-todo" placeholder="{{ __('rundown.add_new_gfx') }}">
        <ul id="gfxDataList">
        </ul>
    </div>
</x-Bootstrap.modal>
<x-Bootstrap.modal id="printModal" size="sm" saveBtn="{{ __('rundown.print') }}" saveClick="printRundown({{ $rundown->id }})" title="{{ __('rundown.print') }}">
	<x-forms.box name="rundown" label="rundown.rundown" wrapClass="ml-2" checked="checked" />
	<x-forms.box name="script" label="rundown.script" wrapClass="ml-2" checked="checked"/>
	<x-forms.box name="notes" label="rundown.notes" wrapClass="ml-2" checked="checked"/>
	<h6 class="ml-2 mt-2 mb-n1 text-secondary"><u>{{ __('rundown.include') }}:</u></h6>
	<x-forms.box name="rundown_meta" label="{{ __('rundown.meta') }}" wrapClass="ml-2 mt-1" checked="checked"/>
	<x-forms.box name="rundown_notes" label="{{ __('rundown.notes') }}" wrapClass="ml-2" checked=""/>
	<x-forms.box name="rundown_script" label="{{ __('rundown.script') }}" wrapClass="ml-2" checked=""/>
</x-Bootstrap.modal>

<!-- /Modals -->
<form name="print-rundown-form" id="print-rundown-form" method="POST" action="{{ route('rundown.print') }}" target='_blank'>
	@csrf
	<div id="print-rundown-form-values">
	</div>
</form>
@endsection
@section('footer_scripts')
<script src="{{ asset('js/rundown.js') }}"></script>
<script>
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$('#teamlist').click(function() {
		$.post('{{ route('rundown.users') }}', 
		{
			users: '{{ json_encode($rundown->users->pluck('id')) }}'
		},
		function(data, status){
    		if (status == 'success'){
				var users = JSON.parse(data);
				$(users).each(function(){
					if (this.active){
						$('#user-active-'+this.user).removeClass('text-secondary').addClass('text-success');
					}
					else{
						$('#user-active-'+this.user).removeClass('text-success').addClass('text-secondary');
					}
				});
			}
  		});
	});
</script>
@if (!$rundown->sortable)
	<script> 
		$( document ).ready(function() { 
			sortable.options.disabled = true;
		}); 
	</script>
@endif
@endsection