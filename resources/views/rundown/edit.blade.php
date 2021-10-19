@extends('layouts.app')
@php 
	$duration = strtotime($rundown->stoptime) - strtotime($rundown->starttime);
	$totalTime = strtotime($rundown->stoptime) - strtotime($rundown->starttime);
@endphp
@section('add_scripts')
	<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
	<script src="{{ asset('js/pusher.min.js') }}"></script>
	<script>
		var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
			cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
		});
		var channel = pusher.subscribe('rundown');
		channel.bind('{{ $rundown->id }}', function(data) {
			console.log(data.message);
			switch (data.message){
				case 'render' :
					Livewire.emit('render');
				break;
			}
		});
	</script>
	<script src="{{ asset('js/Sortable.min.js')}}"></script>
	<script src="{{ asset('js/rundown.js')}}"></script>
@stop
@section('content')
<div class="container-fluid">
	
	<div class="card" style="width: 1400px; overflow-x: scroll; margin: 0 auto;">
		<div class="card-header">
			<a href="/dashboard/rundown">{{ __('rundown.scripts') }}</a><i class="bi bi-caret-right"></i>{{ __('rundown.edit') }}: <cite title="Source Title"> {{ $rundown->title }} </cite>
		</div>	
		<div class="card-body">
			<div class="card text-white bg-custom mb-3 mt-5">
				<div class="card-header">{{ __('rundown.new_row') }}</div>
				@livewire('rundownrow-form', ['rundown' => $rundown])
			</div>
			<table class="table table-bordered table-sm mb-n1 mt-4">
				<tr>
					<td class="text-center">
						<a  href="/dashboard/rundown/{{ $rundown->id }}/editcal"><i class="bi bi-pencil float-right"></i></a>
						<h2>{{ $rundown->title }}</h2>
					</td>
				</tr>
			</table>
			<table class="table table-bordered table-sm mt-n1">
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
					<td>{{ gmdate('H:i', $duration) }}</td>
					</tr>						
				</tbody>
			</table>
			<div id="rundown-edit-table-wrap">
				@livewire('rundown', ['rundown' => $rundown])
			</div>
		</div>
	</div>
</div>
@endsection