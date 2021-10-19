@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-header">
					{{ __('rundown.scripts') }}
				</div>
				<div class="card-body">
					<a href="rundown/create" class="btn btn-dark mb-3">{{ __('rundown.new') }}</a>
					<livewire:rundown-table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection