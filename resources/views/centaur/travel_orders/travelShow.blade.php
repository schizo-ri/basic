<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta name="description" content="{{ Config::get('app.name') }}" >
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Putni nalog</title>
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../css/travel_show.css') }}"/> 
		<link rel="stylesheet" href="{{ URL::asset('/../css/basic.css') }}"/> 
	</head>
	<body>
		<div class="table-responsive">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('travel_orders.update', $travel->id) }}">
			<section class="travel_section">
				<header class="travel_header">
					<h1>Putni nalog br.______ / {{ date('Y') }}</h1>
					<p>Poduzeće: {{ $company->name }} </p>
					<p>Vozilo: 
						<select name="car_id" required>
							<option selected disabled></option>
							@foreach ($cars as $car)
								<option value="{{ $car->id }}" {!! $travel->car_id == $car->id ? 'selected' : '' !!} >{{ $car->model . ' ' .  $car->registration }} {!! $car->enc != null ? ', ENC: ' . $car->enc : '' !!}</option>
							@endforeach
						</select>
					</p>
				</header>
				<main class="travel_main">
					<div class="approval">
						<h4>Odobrenje</h4>
						<p>Određujem da 
							<select class="form-control" name="employee_id" required>
								@foreach ($employees as $employee)
									<option value="{{ $employee->id }}" {!! $travel->employee_id == $employee->id ? 'selected' : '' !!} >{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name'] }}</option>
								@endforeach
							</select> zaposlenik našeg poduzeća, dana <input name="start_date" type="datetime-local" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($travel->start_date )) }}" required>
						</p> 
						<p>službeno otputuje u <input name="destination" type="text" id="destination" class="form-control" value="{{ $travel->destination }}" required  > sa zadaćom <input name="description" type="text" class="form-control" value="{{ $travel->description }}" ></p> 
						<p>Putovanje može trajati <input name="days" type="number" class="form-control" value="{{ $travel->days }}" required> dana.</p> 
						<div class="aproveBy">
							<p class="col-4 float_right">Odobrio: <img src="{{ URL::asset('img/signature.jpg')}}" alt="Potpis"/></p>
						</div>
					</div>
					<div class="calculation">
						<h4>Obračun</h4>
						<p>Za izvršeno službeno putovanje u <span class="destination">{{ $travel->destination }}</span> </p> 
						<section class="table">
							<h5>dnevnice</h5>
							<div class="table">
								<div class="thead">
									<div class="tr">
										<span class="th col-2">Odlazak / datum, sat</span>
										<span class="th col-2">Povratak / datum, sat</span>
										<span class="th col-2">Br. sati</span>
										<span class="th col-2">Br. dnevnica</span>
										<span class="th col-2">Cijena dnevnice</span>
										<span class="th col-2">Iznos</span>
									</div>
								</div>
								<div class="tbody">
									<div class="tr">
										@php
											$date1 = new DateTime($travel->start_date);
											
											$sati = 0;
											$dnevnice = 0;
											if ($travel->end_date ) {
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
											}
										@endphp
										<span class="td col-2 align_c"><input name="start_date" type="datetime-local" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($travel->start_date )) }}" required></span>
										<span class="td col-2 align_c"><input name="end_date" type="datetime-local" class="form-control" value="{!! $travel->end_date ? date('Y-m-d\TH:i', strtotime($travel->end_date )) : '' !!}" ></span>
										<span class="td col-2 align_c">{{ $sati }}</span>
										<span class="td col-2 align_c dnevnice">{{ $dnevnice }}</span>
										<span class="td col-2 align_r">
											@if($dnevnice > 0) 
												<input name="daily_wage" type="number" class="align_r" step="0.01" value="{{ number_format( $travel->daily_wage, 2, '.', '') }}" >
											@endif
										</span>
										<span class="td col-2 align_r total_sum sum_daily_wage">{{ number_format($dnevnice * $travel->daily_wage, 2, '.', '') }}</span>
									</div>
								</div>
							</div>
							<h5>Kilometraža</h5>
							<div class="table">
								<div class="thead">
									<div class="tr">
										<span class="th col-3">Od</span>
										<span class="th col-3">Do</span>
										<span class="th col-2">km</span>
										<span class="th col-2">Cijena / 1 km</span>
										<span class="th col-2">Iznos</span>
									</div>
								</div>
								<div class="tbody">
									@if ($locco1)
										<input type="hidden" name="locco_id" value="{{ $locco1->id }}">
										<div class="tr">
											<span class="td col-3">{{ $locco1->starting_point }}</span>
											<span class="td col-3">{{ $locco1->destination }}</span>
											<span class="td col-2 distance align_c">{{ $locco1->distance }}</span>
											<span class="td col-2 km_price align_r">{!! $locco1->car['private_car'] == 1 ? 2.00 : '' !!}</span>
											<span class="td col-2 summary align_r total_sum">{!! $locco1->car['private_car'] == 1 ? number_format($locco1->distance * 2, 2, '.', '') : '' !!}</span>
										</div>
									@endif
									@if (count($loccos) > 0)
										@php
											$i = 0;
										@endphp
										@foreach ($loccos as $locco)
											<input type="hidden" name="locco_id[{{$i}}]" value="{{ $locco->id }}">
											<div class="tr">
												<span class="td col-3"><input class="form-control starting_point" name="starting_point[{{$i}}]" type="text" value="{{ $locco->starting_point }}"  /></span>
												<span class="td col-3"><input class="form-control" name="km_destination[{{$i}}]" type="text" value="{{ $locco->destination }}"  /></span>
												<span class="td col-2 distance align_c"><input class="form-control align_c" name="distance[{{$i}}]" type="number" value="{{ $locco->distance }}"  /></span>
												<span class="td col-2 km_price align_r">{!! $travel->car['private_car'] == 1 ? 2.00 : '' !!}</span>
												<span class="td col-2 summary align_r total_sum">{!! $travel->car['private_car'] == 1 ? number_format($locco->distance * 2, 2, '.', '') : '' !!}</span>
											</div>
											@php
												$i++;
											@endphp
										@endforeach
										@for ($j = $i; $j < 3; $j++)
											<div class="tr">
												<span class="td col-3"><input class="form-control starting_point" name="starting_point[{{$j}}]" type="text" value="{{ old('starting_point') }}"  /></span>
												<span class="td col-3"><input class="form-control" name="km_destination[{{$j}}]" type="text" value="{{ old('km_destination') }}"  /></span>
												<span class="td col-2 distance align_c"><input class="form-control align_c" name="distance[{{$j}}]" type="number" value="{{ old('distance') }}"  /></span>
												<span class="td col-2 km_price align_r">{!! $travel->car['private_car'] == 1 ? 2.00 : '' !!}</span>
												<span class="td col-2 summary align_r total_sum"></span>
											</div>
										@endfor
									@else
										@for ($i = 0; $i < 3; $i++)
											<div class="tr">
												<span class="td col-3"><input class="form-control starting_point" name="starting_point[{{$i}}]" type="text" value="{{ old('starting_point') }}"  /></span>
												<span class="td col-3"><input class="form-control" name="km_destination[{{$i}}]" type="text" value="{{ old('km_destination') }}"  /></span>
												<span class="td col-2 distance align_c"><input class="form-control align_c" name="distance[{{$i}}]"  type="number" value="{{ old('distance') }}"  /></span>
												<span class="td col-2 km_price align_r">{!! $travel->car['private_car'] == 1 ? 2.00 : '' !!}</span>
												<span class="td col-2 summary align_r total_sum"></span>
											</div>
										@endfor
									@endif
								</div>
							</div>
							<h5>Ostali troškovi</h5>
							<div class="table">
								<div class="thead">
									<div class="tr">
										<span class="th col-2">Br.računa</span>
										<span class="th col-5">Opis troška</span>
										<span class="th col-2">Iznos</span>
										<span class="th col-1">Valuta</span>
										<span class="th col-2">Iznos</span>
									</div>
								</div>
								<div class="tbody">
									@php
										$j = 0;
									@endphp
									@foreach ($travel->expenses as $expense)
										<div class="tr expences">
											<input type="hidden" name="expence_id[{{ $j }}]" value="{{ $expense->id }}" >
											<span class="td col-2"><input name="bill[{{ $j }}]" type="text" value="{{ $expense->bill }}" class="bill" ></span>
											<span class="td col-5"><input name="cost_description[{{ $j }}]" type="text" value="{{ $expense->cost_description }}" class="cost_description" ></span>
											<span class="td col-2 align_r"><input name="amount[{{ $j }}]" type="number" class="align_r amount" step="0.01" value="{{ number_format($expense->amount, 2, '.', '') }}"  ></span>
											<span class="td col-1 align_c"><input name="currency[{{ $j }}]" type="text" class="align_c currency" value="{{ $expense->currency }}" ></span>
											<span class="td col-2 align_r total_amount"><input name="total_amount[{{ $j }}]" type="text" class="align_r total_sum" type="number" step="0.01" value="{{ $expense->total_amount }}" ></span>
										</div>
										@php
											$j++;
										@endphp
									@endforeach
									@for ($i = $j; $i < 6; $i++)
										<div class="tr expences">
											<span class="td col-2"><input name="bill[{{ $i }}]" class="bill" type="text"></span>
											<span class="td col-5"><input name="cost_description[{{ $i }}]" class="cost_description" type="text"></span>
											<span class="td col-2 align_c"><input name="amount[{{ $i }}]" class="align_r amount" type="number" step="0.01" ></span>
											<span class="td col-1 align_c"><input name="currency[{{ $i }}]" class="align_c currency" type="text"></span>
											<span class="td col-2 align_r total_amount"><input name="total_amount[{{ $i }}]" class="align_r total_sum" type="number" step="0.01" ></span>
										</div>
									@endfor
								</div>
							</div>
							<h5 class="align_r"><span class="col-2 float_right display_inline_block" id="total_sum">0,00</span><span class="float_right">Sveukupno</span></h5>
						</section>
					</div>
				</main>
				<footer>
					<p>Primljen predujam dana 
						<input name="advance_date" type="date" class="form-control align_c" value="{!! $travel->advance_date ? $travel->advance_date : '' !!}" >
						u iznosu 
						<input name="advance" type="number" step="0.01" class="form-control align_c" value="{{ number_format( $travel->advance, 2, '.', ' ') }}" id="advance" /> Kn</p>
					<p>Ostaje za isplatu / povrat <input name="rest_payout" id="rest_payout" type="number" step="0.01" class="form-control align_c" value="" > Kn</p>  
					
					{{--  {{ number_format( $travel->rest_payout, 2, '.', '')}} --}}
					<p>Podnositelj obračuna 
						<select class="form-control" name="calculate_employee" >
						<option selected disabled></option>
						@foreach ($employees as $employee)
							<option value="{{ $employee->id }}" {!! $travel->calculate_employee == $employee->id ? 'selected' : '' !!}>{{ $employee->user['first_name'] . '' .  $employee->user['last_name'] }}</option>
						@endforeach
						</select>
						, {{ $company->city }}, Direktor  Zrinka Runje Klasan
					</p>
				</footer>
				@if(Sentinel::getUser()->hasAccess(['travel_orders.update']))
					<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
				@endif
			</section>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
		</div>	
		</form>
		<!-- Datatables -->
		<script src="{{ URL::asset('/../dataTables/datatables.min.js') }}"></script>
		<script src="{{ URL::asset('/../dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
		<script src="{{ URL::asset('/../dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
		<script src="{{ URL::asset('/../dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
		<script>
			$('#destination').change(function(){
				$('.destination').text($(this).val());
			});
			$('input.starting_point').change(function(){
				if($(this).val() != '') {
					$(this).attr('required','required');
					$(this).parent().siblings().find('input').attr('required','required');
				} else {
					$(this).removeAttr('required');
					$(this).parent().siblings().find('input').removeAttr('required');
				}
			});
			$('input.bill').change(function(){
				if($(this).val() != '') {
					$(this).attr('required','required');
					$(this).parent().siblings().find('input').attr('required','required');
				} else {
					$(this).removeAttr('required');
					$(this).parent().siblings().find('input').removeAttr('required');
				}
			});
			$('input.cost_description').change(function(){
				if($(this).val() != '') {
					$(this).attr('required','required');
					$(this).parent().siblings().find('input').attr('required','required');
				} else {
					$(this).removeAttr('required');
					$(this).parent().siblings().find('input').removeAttr('required');
				}
			});
			$('input.amount').change(function(){
				if($(this).val() != '') {
					$(this).attr('required','required');
					$(this).parent().siblings().find('input').attr('required','required');
				} else {
					$(this).removeAttr('required');
					$(this).parent().siblings().find('input').removeAttr('required');
				}
			});
			$('span.distance input').change(function(){
				var km_price = $(this).parent().next('.km_price').text();
				var distance = $(this).val();
				$(this).parent().siblings('.summary').text((km_price * distance).toFixed(2));
				if($(this).val() != '') {
					$(this).attr('required','required');
					$(this).parent().siblings().find('input').attr('required','required');
				} else {
					$(this).removeAttr('required');
					$(this).parent().siblings().find('input').removeAttr('required');
				}
				total_sum();
			});
			$('input[name=start_date]').change(function(){
				var start_date = $(this).val();
			
				$('input[name=start_date]').val(start_date);
				$('input[name=end_date]').val(start_date);
			});
			$('input[name=daily_wage]').change(function(){
				var dnevnica = $(this).val();
				var broj_dnevnica = $('.dnevnice').text();
			
				$('.sum_daily_wage').text(dnevnica * broj_dnevnica);
				total_sum();
			});
			$('input.amount').change(function(){
				console.log($( this ).val());
				$( this ).parent().siblings('.total_amount').find('input').val($( this ).val());
				total_sum();
			});
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
			$('#advance').change(function(){
				total_sum();
			});
		</script>
	</body>
</html>