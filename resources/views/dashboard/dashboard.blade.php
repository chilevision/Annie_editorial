@extends('layouts.app')
@section('add_styles')
	<link rel="stylesheet" href="{{ asset('css/simple-calendar.css') }}" />
@stop
@section('content')
<div class="container" onload="toggleMenuActive()">
    <div class="row">
        <div class="p-2 mb-4 bg-light border rounded-3">
			<div class="container-fluid py-3">
				<h1>{{ __('dashboard.welcome') }}</h1>
				<img src="{{ asset('css/img/stack_re-order_01.png') }}" alt="Caspar" class="mx-auto">
				<p>{{ __('dashboard.intro') }}</p>
				<a class="btn btn-secondary" href="https://github.com/CasparCG/help/wiki" target="_blank">{{ __('dashboard.read_more') }}</a>
				<table class="table table-striped mt-3">
					<thead class="thead-dark">
						<tr>
							<th>{{ __('dashboard.rundown_count') }}</th>
							<th>{{ __('dashboard.next_run') }}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{{$rundowns}}</td>
							<td>{{$nextRun}}</td>
						</tr>
					</tbody>
				</table>
				<div id="calendar"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="p-2 bg-light border rounded-3 container-fluid">
			<div class="container-fluid py-3">
				<h2>{{ __('dashboard.info') }}</h2>
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
							<img src="{{ asset('css/img/tutorial/infocal.png') }}" alt="calendar">
							<p>I nästa vy kan du börja planera din sändning. Det första du behöver göra är att döpa din sändning i rutan ”Grundläggande info”. Konsollera samtidigt att datum och tid stämmer! I rutan ”Produktionsroller” kan du lägga till teammedlemmar och tilldela denne en roll. Prata med ansvarig lärare om du saknar en rollbeskrivning! I rutan ”Körschema planerar du din sändning. Lägg till ett avsnitt genom att klicka på plus-symbolen nere i vänstra hörnet. Här får du alternativen ”Inslag” och ”Steg” där inslag innebär en mediafil som spelas upp av inslagsservern och steg innebär en kameraposition.</p>
							<img src="{{ asset('css/img/tutorial/infoplus.png') }}" alt="plus">
							<p>När du lagt till ett avsnitt skapas en rad i ditt körschema. Här fyller du i all nödvändig information för att sändningen ska fungera.</p>
							<p>Alternativen för ljud är MX, Live och MX/Live, där MX står för förmixat ljud och live för ljud som kommer från studion. Denna info är nödvändig för ljudteknikern i produktionen. 
Beroende på om du valt ett inslag eller steg får du några olika alternativ. Väljer du inslag så klickar du på knappen inslag för att välja den mediafil du vill lägg till körschemat och rutorna ”inslag” och ”tid” fylls automatiskt i av systemet. Väljer du steg så har du istället möjlighet att välja vilken kamera som ska användas. I rutan ”inslag” kan du fylla i information om vad som händer under detta avsnitt. Tiden fyller du också i som minuter, sekunder och bildrutor. 
Slutord anger vad det sista som sägs i ett inslag eller avsnitt är.</p>
							<p>Alla avsnitt i ditt körschema kan ha grafik och/eller telepromptertext kopplade till sig. Lägg till genom att välja någon av knapparna ”Lägg till grafik” eller ”Lägg till text”.</p>
							<p>När du vill lägga till grafik börjar du med att välja den mall du vill använda i listan ”Template”. Du kan lägga till och ta bort text med plus och minus knapparna. Du kan bara lägga till text på mallarnas förutbestämda fält. Föra att skapa en mall se avsnittet ”Skapa mallar” här nedan.</p>
							<img src="{{ asset('css/img/tutorial/infogfx.png') }}" alt="graphics">
							<p>I fältet för längd skriver du, i sekunder, hur länge grafiken ska synas i bild. Lager anger vilket lager grafiken hamnar på. Caspar kan bara hantera en grafikfil per lager samtidigt. Du lägger till en ny grafikfil genom att klicka på knappen ”Ny grafik”. Då skapas en ny ruta och du upprepar föregående steg. När du är klar klickar du på ”Spara grafik”.</p>
							<p>När du lägger till ett inslag kan du bocka i rutan ”Kryssa” som innebär att videofilen kommer att spela upp nästa inslag direkt den har spelat färdigt.</p>
							<p>För att ta bord en rad klickar du på krysset längst till höger på raden du inte vill ha kvar. Körordningen går också att ändra genom att ta tag i en rad och dra den uppåt och nedåt i listan.</p>
						</div>
					</div>

					<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
						<div class="panel-body">
							<p>Mallarna används för att göra dynamisk grafik (där texten kan ändras). Enklare mallar går att skapa i photoshop. Mer avancerade mallar går att skapas i Adobe Flash, Adobe Animate eller html5. För flash och animate finns en bra tutorial här nedan. Som standard namnges textfält med f-systemet vilket innebär att ditt första textfält får namnet "f0", nästa får "f1" osv. Namngivningen är viktig för att Caspar ska veta vilket fält som ska ha vilken text. Du kan givetvis använda vilket system du vill för att namnge textfälten.</p>
							<div class="embed-responsive embed-responsive-16by9">
								<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/DgmoS-RxcwE" frameborder="0" allowfullscreen></iframe>
							</div>
							<p>När du har skapat din mall laddar du upp den här i webbverktyget genom att gå in på "Mallar". Här kan du också dela med dig av din mall till alla andra användare av systemet genom att klicka på knappen "Dela ut".
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>
@endsection
@section('footer_scripts')
<script src="{{ asset('js/jquery.simple-calendar.min.js') }}"></script>
<script>
	$(function(){
  $("#calendar").simpleCalendar();
});
</script>
@endsection