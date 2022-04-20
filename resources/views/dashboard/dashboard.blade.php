@extends('layouts.app')
@section('add_styles')
	<link rel="stylesheet" href="{{ asset('css/simple-calendar.css') }}" />
@stop
@section('content')
<div class="container light-style flex-grow-1 container-p-y">
	<div class="card mt-4">
		<div class="card-header">
			<h1 class="text-center dashboard">{{ __('dashboard.welcome') }}</h1>
		</div>
		<div class="card-body w-75 mx-auto">
				<img class="img-fluid" src="{{ asset('css/img/annie.jpg') }}" alt="Caspar" class="mx-auto">
				<p>{{ __('dashboard.intro') }}</p>
				<a class="btn btn-secondary" href="https://github.com/CasparCG/help/wiki" target="_blank">{{ __('dashboard.read_more') }}</a>
		</div>
	</div>

	<div class="card mt-4">
		<div class="card-header">
			<h3 class="text-center dashboard">{{ __('rundown.schedule') }}</h3>
		</div>
		<div class="card-body w-75 mx-auto">
			<x-calendar/>
			<div class="bg-custom text-white pt-2 pr-2 pb-2 pl-2"><div class="row"><h6 class="col-3">{{ __('dashboard.rundown_count') .' '. $rundowns}}</h6><h6 class="col">{{ __('dashboard.next_run') .' '. $nextRun}}</h6></div></div>
		</div>
	</div>
	<div class="card mt-4">
		<div class="card-header">
			<h3 class="text-center dashboard">{{ __('dashboard.info') }}</h3>
		</div>
		<div class="card-body">
			<div class="btn-group" role="group" aria-label="Basic outlined example">
				<a class="btn btn-secondary" data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="false" aria-controls="collapseOne">{{ __('dashboard.btn_info') }}</a>
				<a class="btn btn-secondary" data-toggle="collapse" href="#collapseTwo" role="button" aria-expanded="false" aria-controls="collapseTwo">{{ __('dashboard.btn_using') }}</a>
				<a class="btn btn-secondary" data-toggle="collapse" href="#collapseThree" role="button" aria-expanded="false" aria-controls="collapseThree">{{ __('dashboard.btn_templates') }}</a>
			</div>
			<div class="panel panel-default mt-2">
				<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body">
						<p>CasparCG server och CasparCG client tillsammans med den här hemsidan och programmet Caspar Controller är högskolans huvudsakliga system för att spela ut video och grafik vid flerkameraproduktion.</p> 
						<p>Det har visat sig att en del verktyg inte fungerar fullt ut med Google Chrome. Det är därför rekommenderat att använda Safari eller nyare versioner av internet explorer.
					</div>
				</div>

				<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
					<div class="panel-body">
						<p>Detta webbverktyg används i förarbetsfasen av en sändning. Här planeras och klockas sändningens olika dela såsom videoinslag, och kameravinklar. För att börja planera en ny sändning gå in på ”Körschema” och välj ”skapa ny”. I Kalendervyn som visas börjar du med att Flytta det blå blocket till det datum då sändningen ska äga rum. Klicka sedan på det valda datumet för att välja sändningens tidpunkt. När du gjort det, klicka på ”gå vidare”.</p>
						
					</div>
				</div>

				<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
					<div class="panel-body">
						<p>Mallarna används för att göra dynamisk grafik (där texten kan ändras). Enklare mallar går att skapa i photoshop. Mer avancerade mallar går att skapas i Adobe Flash, Adobe Animate eller html5. För flash och animate finns en bra tutorial här nedan. Som standard namnges textfält med f-systemet vilket innebär att ditt första textfält får namnet "f0", nästa får "f1" osv. Namngivningen är viktig för att Caspar ska veta vilket fält som ska ha vilken text. Du kan givetvis använda vilket system du vill för att namnge textfälten.</p>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection
@section('footer_scripts')
<script src="{{ asset('js/jquery.simple-calendar.min.js') }}"></script>

@endsection