<!DOCTYPE html>
<html lang="hr" style="font-size: 12px">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Putni nalog</title>
		<style>@page { margin:20px; }</style>
	</head>
	
	<body style="font-family: DejaVu Sans;">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('travel_orders.update', $travel->id) }}">
			<table id="index_table" class="display table table-hover sort_1_desc">
				<section class="travel_section" style="padding: 15px 20px;overflow: hidden;">
					<header class="travel_header">
						<h1>Putni nalog br.______ / {{ date('Y') }}</h1>
						<p style="font-family: DejaVu Sans;">Poduzeće: {{ $company->name }} </p>
						<p>Vozilo: {{ $travel->car->model . ' ' .  $travel->car->registration }},  ENC: {{ $travel->car->enc }}
						</p>
					</header>
					<main class="travel_main" >
						<div class="approval" >
							<h4 style="text-transform: uppercase;">Odobrenje</h4>
							<p>Određujem da {{ $travel->employee->user['first_name'] . ' ' .  $travel->employee->user['last_name'] }}
								zaposlenik našeg poduzeća, dana {{ date('Y-m-d', strtotime($travel->start_date )) }}</p> 
							<p>službeno otputuje u {{ $travel->destination }} sa zadaćom {{ $travel->description }}</p> 
							<p>Putovanje može trajati {{ $travel->days }} dana.</p> 
							<div class="aproveBy" style="width:100%, display:block; clear: right; " >
								<p class="col-4" style="float: right;" >Odobrio: <img src="{{ public_path() . '/img/signature.jpg' }}" alt="Potpis" style="max-height: 40px;margin-left: 20px;" /></p> 
							</div>
						</div>
						<div class="calculation" >
							<h4 style="text-transform: uppercase;">Obračun</h4>
							<p>Za izvršeno službeno putovanje u <span class="destination">{{ $travel->destination }}</span> </p> 
							<section class="table" >
								<h5 style="text-transform: uppercase;margin:0">dnevnice</h5>
								<table class="table" style="border:1px solid #000000;width:100%;max-width:100%;">
									<thead class="thead" style="width:100%;max-width:100%;">
										<tr class="tr">
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;" >Odlazak / datum, sat</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Povratak / datum, sat</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Br. sati</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Br. dnevnica</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Cijena dnevnice</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;">Iznos</th>
										</tr>
									</thead>
									<tbody class="tbody" style="width:100%;max-width:100%;">
										<tr class="tr">
											@php
												$date1 = new DateTime($travel->start_date);
												$date2 = new DateTime($travel->end_date);
												$date_diff = $date2->diff($date1);
												$sati = $date_diff->h + ($date_diff->d*24);
												$dnevnice = $date_diff->d;
												if($dnevnice == 0 && $date_diff->h != 0 ) {
													if($date_diff->h >= 8 && $date_diff->h < 12) {
														$dnevnice += 0.5; 
													} else if ($date_diff->h >= 12) {
														$dnevnice += 1; 
													}
												} else {
													if($date_diff->h != 0) {
														if($date_diff->h < 12) {
															$dnevnice += 0.5; 
														} else if ($date_diff->h >= 12) {
															$dnevnice += 1; 
														}
													}
													
												}
												$total_sum = 0.00;
												$start = date('d.m.Y H:i', strtotime($travel->start_date ));
												$end = date('d.m.Y H:i', strtotime($travel->end_date ));

												$total_sum += $dnevnice * $travel->daily_wage;
												
											@endphp
											<td class="td col-2 align_c" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $start }}</td>
											<td class="td col-2 align_c" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $end }}</td>
											<td class="td col-2 align_c" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:center;">{{ $sati }}</td>
											<td class="td col-2 align_c" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:center;">{{ $dnevnice }}</td>
											<td class="td col-2 align_r" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:right;">{{ $travel->daily_wage }}</td>
											<td class="td col-2 align_r total_sum" style="width: 16.66666667%; border-bottom:1px solid #000000;text-align:right;">{{ number_format($dnevnice * $travel->daily_wage, 2, '.', '') }}</td>
										</tr>
									</tbody>
								</table>
								<h5 style="text-transform: uppercase;margin:0">Kilometraža</h5>
								<table class="table" style="border:1px solid #000000;width:100%;max-width:100%;">
									<thead class="thead" style="width:100%;max-width:100%;">
										<tr class="tr" style="width:100%;max-width:100%;">
											<th class="th col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Od</th>
											<th class="th col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Do</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:center;">km</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:center;">Cijena / 1 km</th>
											<th class="th col-2" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:right;">Iznos</th>
										</tr>
									</thead>
									<tbody class="tbody" style="width:100%;max-width:100%;">
										@if ( $locco )
											<tr class="tr" style="width:100%;max-width:100%;">
												<td class="td col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $locco->starting_point }}</td>
												<td class="td col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $locco->destination }}</td>
												<td class="td col-2 distance align_c" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{strval( $locco->distance )}}</td>
												<td class="td col-2 km_price align_r" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:center;">{!! $travel->car['private_car'] == 1 ? 2.00 : '' !!}</td>
												<td class="td col-2 summary align_r total_sum" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:right;">{!! $travel->car['private_car'] == 1 ? number_format($locco->distance * 2, 2, '.', '') : '' !!}</td>
											</tr>
											
										@endif
										@if (count($loccos) > 0)
											@foreach ($loccos as $locco1)
												@php
													if ($travel->car['private_car'] == 1) {
														$total_sum += $locco1->distance * 2;
													}
												@endphp
												<tr class="tr" style="width:100%;max-width:100%;">
													<td class="td col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $locco1->starting_point }}</td>
													<td class="td col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $locco1->destination }}</td>
													<td class="td col-2 distance align_c" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{strval( $locco1->distance )}}</td>
													<td class="td col-2 km_price align_r" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:center;">{!! $travel->car['private_car'] == 1 ? 2.00 : '' !!}</td>
													<td class="td col-2 summary align_r total_sum" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:right;">{!! $travel->car['private_car'] == 1 ? number_format($locco1->distance * 2, 2, '.', '') : '' !!}</td>
												</tr>
											@endforeach
										@else
											@for ($i = 0; $i < 3; $i++)
												<tr class="tr" style="width:100%;max-width:100%;">
													<td class="td col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
													<td class="td col-3" style="width: 25%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
													<td class="td col-2 distance align_c" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
													<td class="td col-2 km_price align_r" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
													<td class="td col-2 summary align_r total_sum" style="width: 16.66666667%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
												</tr>
											@endfor
										@endif
									</tbody>
								</table>
								<h5 style="text-transform: uppercase;margin:0">Ostali troškovi</h5>
								<table class="table" style="border:1px solid #000000;width:100%;max-width:100%;">
									<thead class="thead" style="width:100%;max-width:100%;">
										<tr class="tr" style="width:100%;max-width:100%;">
											<th class="th col-2" style="width:15%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Br.računa</th>
											<th class="th col-5" style="width: 45%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Opis troška</th>
											<th class="th col-2" style="width:15%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Iznos</th>
											<th class="th col-1" style="width:10%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Valuta</th>
											<th class="th col-2" style="width: 15%; border-bottom:1px solid #000000;border-right:1px solid #000000;">Iznos</th>
										</tr>
									</thead>
									<tbody class="tbody" style="width:100%;max-width:100%;">
										@php
											$j = 0;
										@endphp
										@foreach ($travel->expenses as $expense)
										@php
											$total_sum += $expense->total_amount;
										@endphp
											<tr class="tr expences" style="width:100%;max-width:100%;">
												<td class="td col-2" style="width: 15%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $expense->bill }}</td>
												<td class="td col-5" style="width:45%; border-bottom:1px solid #000000;border-right:1px solid #000000;">{{ $expense->cost_description }}</td>
												<td class="td col-2 align_r" style="width: 15%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:right;">{{ number_format($expense->amount, 2, '.', '') }}</td>
												<td class="td col-1 align_c" style="width:10%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:center;">{{ $expense->currency }}</td>
												<td class="td col-2 align_r" style="width: 15%; border-bottom:1px solid #000000;border-right:1px solid #000000;text-align:right;">{{ number_format($expense->total_amount, 2, '.', '') }}</td>
											</tr>
											@php
												$j++;
											@endphp
										@endforeach
										@for ($i = $j; $i < 6; $i++)
											<tr class="tr expences" style="width:100%;max-width:100%;height:15px;line-hight:15px">
												<td class="td col-2" style="width: 15%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
												<td class="td col-5" style="width: 45%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
												<td class="td col-2 align_c" style="width: 15%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
												<td class="td col-1 align_c" style="width:10%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
												<td class="td col-2 align_r" style="width: 15%; border-bottom:1px solid #000000;border-right:1px solid #000000;height:15px;line-hight:15px"></td>
											</tr>
										@endfor
									</tbody>
								</table>
								<h5 class="align_r" style="text-transform: uppercase;float:right; margin-right: 15px;"><span class="float_right">Sveukupno</span><span class="col-2 float_right display_inline_block" id="total_sum" style="margin-left: 15px">{{ $total_sum }}</span></h5>
							</section>
						</div>
					</main>
					<footer>
						<p>Primljen predujam dana {!! $travel->advance_date ? date('d.m.Y', strtotime($travel->advance_date)) : '________' !!} u iznosu {{ number_format( $travel->advance, 2, '.', '') }} Kn</p>
						<p>Ostaje za isplatu / povrat {{ number_format( $travel->rest_payout, 2, '.', '')}} Kn</p>
						<p>Podnositelj obračuna {{ $travel->employee->user['first_name'] . ' ' .  $travel->employee->user['last_name'] }}
						</p>
					</footer>
				</section>
			</table>
		</form>
		<script>
			total_sum();
			function total_sum() {
				var total = 0;
				var advance = $('#advance').val();
				$( ".total_sum" ).each(function( index ) {
					var value = '';
					if ( $( this ).val() != '') {
						value = $( this ).val();
					} else if ( $( this ).text() != '') {
						value = $( this ).text();
					}
				
					if( $.isNumeric( value ) ) {
						total += Number( value );
					}
				});
				$('#total_sum').text(total.toFixed(2));
				if(advance) {
					total = total-advance;
				}
				$('#rest_payout').val(total.toFixed(2));
			}

		</script>
	</body>
</html>