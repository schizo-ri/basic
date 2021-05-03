@extends('Centaur::layout')

@section('title', __('basic.contract'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="header_document">
				<a class="link_back" href="{{ route('contracts.index') }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.contract') {{ $contract->template->name }}
				<span id="contract_id" hidden >{{ $contract->id }}</span>
			</div>
			<main class="all_documents">
				<div class="col-xs-12 offset-xs-0 col-sm-10 offset-sm-1 col-md-8 offset-md-2 show_contract">
					<section class="contract_preview">
						<header class="contract_header">
							<div class="logo col-3">
								@if(file_exists('../public/storage/company_img/logo.png'))
									<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo"/>
								@else 
									<img src="{{ URL::asset('icons/intranet_logo.png')}}" alt="company_logo"/>
								@endif
							</div>
							<div>
								<p>
									<b>{{ $company->name }}</b>, sa sjedištem u Republici Hrvatskoj, {{ $company->city }}, {{ $company->address }}, OIB: {{ $company->oib }}, zastupano po direktoru {{ $company->director }} <span class="italic font_12">(u daljnjem tekstu: "Najmodavac")</span>
								</p>
								<p class="align_c">i</p>
								<p>
									<b>{{ $contract->customer->name }}</b>, sa sjedištem u Republici Hrvatskoj, {{ $contract->customer->city }}, {{ $contract->customer->address }}, OIB: {{ $contract->customer->oib }}, zastupano po direktoru {{ $contract->customer->representedBy }} <span class="italic font_12">(u daljnjem tekstu: "Najmoprimac")</span>
								</p>
								<p class="italic font_12">(u daljnjem tekstu zajedno: „ugovorne strane“)</p>
								<p>sklopili su dana {{ date('d.m.Y.', strtotime($contract->date)) }} godine sljedeći</p>
							</div>
						</header>
						<main>
							<div class="header_title">
								<h2>UGOVOR br. {{ $contract->contract_no }}</h2>
								<h2>{{ $contract->template->name }}</h2>
								<p class="italic font_12 align_c">(u daljnjem tekstu: „Ugovor“)</p>
							</div>
							<div>
								@foreach ($contract->template->hasArticles as $key => $article)
									<p class="italic bold align_c">Članak {{ $key +1  }}.</p>
									<div>
										{!! $article->article_text !!}
									</div>
								@endforeach
							</div>
							@if ($contract->template->general_conditions)
								{!! $contract->template->general_conditions !!}
							@endif
							<div>

							</div>
						</main>
						<footer>
							<div>
								<div class="col-6 float_left">
									<p class="align_c">{{ $company->name }}</p>
									<p class="signature">

									</p>
									<p class="align_c">direktor, {{ $company->director }}</p>
								</div>
								<div class="col-6 float_left">
									<p class="align_c">{{  $contract->customer->name }}</p>
									<p class="signature">

									</p>
									<p class="align_c">direktor, {{ $contract->customer->representedBy}}</p>
								</div>
							</div>
							
						</footer>
					</section>
				</div>
			</main>
		</section>
	</main>
</div>
<script>
	var contract_id = $('#contract_id').text();
	var jqxhr = $.get(location.origin + '/getConctract?id='+ contract_id, function( data ) {
		contract = data.contract;
		locations =  data.locations;

		console.log(contract);
		console.log(locations);

		$('#invoice_no').val(contract.invoice_no);
		$('#invoice_date').val(contract.invoice_date);
		$('#duration').val(contract.duration);
		$.each(contract.has_subjects, function( index, subject ) {
			var location =  $.grep(locations, function(e){ return e.id == subject.location_id; });
			
			if( index == 0 ) {
				$('.clanak_1 tbody tr').first().find('.subject_name').val(subject.name + ' ' + subject.serial_no);
				$('.clanak_1 tbody tr').first().find('.counter_bw').val(subject.counter_bw );
				$('.clanak_1 tbody tr').first().find('.counter_c').val(subject.counter_c);
				$('.clanak_1 tbody tr').first().find('.location_id').val(location[0].address + ", "+location[0].city);
				$('.clanak_2 tbody tr').first().find('.subject_name').val(subject.name + ' ' + subject.serial_no);
				$('.clanak_2 tbody tr').first().find('.price_a4_bw').val(subject.price_a4_bw);
				$('.clanak_2 tbody tr').first().find('.price_a4_c').val(subject.price_a4_c);
			} else {
				$('.clanak_1 tbody tr').first().clone().appendTo('.clanak_1 tbody');
				$('.clanak_1 tbody tr').last().find('.subject_name').val(subject.name+ ' ' + subject.serial_no);
				$('.clanak_1 tbody tr').last().find('.counter_bw').val(subject.counter_bw );
				$('.clanak_1 tbody tr').last().find('.counter_c').val(subject.counter_c);
				$('.clanak_1 tbody tr').last().find('.location_id').val(location[0].address + ", "+location[0].city);
				$('.clanak_2 tbody tr').first().clone().appendTo('.clanak_2 tbody');
				$('.clanak_2 tbody tr').last().find('.subject_name').val(subject.name + ' ' + subject.serial_no);
				$('.clanak_2 tbody tr').last().find('.price_a4_bw').val(subject.price_a4_bw);
				$('.clanak_2 tbody tr').last().find('.price_a4_c').val(subject.price_a4_c);
			}
			if (contract.package_prints_bw || contract.package_prints_c ) {
				$('.test_option').show();
				$('.package_prints_bw').val(contract.package_prints_bw);
				$('.package_prints_c').val(contract.package_prints_c);
			}
			if( contract.debenture_amount ) {
				$('.debenture').show();
				$('.debenture_amount').val(contract.debenture_amount);
			}
			if( contract.a3 == 1 ) {
				$('.a3').show();
			}


		});
	})
		.done(function() {
			/* alert( "second success" ); */
	})
		.fail(function() {
			alert( "Došlo je do pogreške kod učitavanja podataka!" );
	})
		.always(function() {
			/* alert( "finished" ); */
	});
		
</script>
@stop