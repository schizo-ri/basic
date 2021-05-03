@extends('Centaur::layout')

@section('title', __('basic.edit_contract'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="header_document">
				<a class="link_back" href="{{ route('contracts.index') }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.edit_contract')
			</div>
			<main class="all_documents">
				<div class="col-xs-12 offset-xs-0 col-sm-10 offset-sm-1 col-md-8 offset-md-2 main_create_contract">
					<div class="customer_group">
						<form class="customer_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('customers.store') }}">
							<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
								<input class="form-control" placeholder="{{ __('basic.name')}}" name="customer_name" type="text" maxlength="100" value="{{ old('name') }}"  />
								{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
							</div>
							<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
								<input class="form-control" placeholder="{{ __('basic.address')}}" name="customer_address" type="text" maxlength="100" value="{{ old('address') }}"  />
								{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
							</div>
							<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
								<input class="form-control" placeholder="{{ __('basic.city')}}" name="customer_city" type="text" maxlength="50" value="{{ old('city') }}"  />
								{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
							</div>
							<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
								<input class="form-control" placeholder="{{ __('basic.oib')}}" name="customer_oib" maxlength="20" type="text" value="{{ old('oib') }}"  />
								{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
							</div>
							<div class="form-group {{ ($errors->has('representedBy')) ? 'has-error' : '' }}">
								<input class="form-control" placeholder="{{ __('basic.director')}}" name="customer_representedBy" maxlength="100" type="text" value="{{ old('representedBy') }}"  />
								{!! ($errors->has('representedBy') ? $errors->first('representedBy', '<p class="text-danger">:message</p>') : '') !!}
							</div>
							@csrf
							<input class="submit_form" type="submit" value="{{ __('basic.add_customer')}}">
						</form>
						<span class="close_customer cursor">Zatvori</span>
					</div>
					<form class="form_contract" accept-charset="UTF-8" role="form" method="post" action="{{ route('contracts.update', $contract->id) }}">
						<fieldset>
								<p class="contract_title">Ugovor</p>
								<div class="form-group {{ ($errors->has('template_id')) ? 'has-error' : '' }}">
									<label>@lang('basic.contract_template')</label>
									<select class="form-control" name="template_id" value="{{ old('template_id') }}" required>
										@foreach ($templates as $template)
											<option value="{{ $template->id }}" {!! $contract->template_id == $template->id ? 'selected' : '' !!}>Ugovor {{ $template->name }}</option>
										@endforeach	
									</select>
									{!! ($errors->has('customer_id') ? $errors->first('customer_id', '<p class="text-danger">:message</p>') : '') !!}
								</div>
								<div class="form-group {{ ($errors->has('customer_id')) ? 'has-error' : '' }}">
									<label>@lang('basic.customer')</label>
									<div class="select_customer">
										<select class="form-control select_filter" name="customer_id" value="{{ old('customer_id') }}" id="customer_id" required>
											<option value="" selected disabled></option>
											@foreach ($customers as $customer)
												<option value="{{ $customer->id }}"{!! $contract->customer_id == $customer->id ? 'selected' : '' !!}>{{ $customer->name }}</option>
											@endforeach	
										</select>
									</div>									
									{!! ($errors->has('customer_id') ? $errors->first('customer_id', '<p class="text-danger">:message</p>') : '') !!}
									<span class="add_customer cursor">Unesi novog naručitelja</span>
								</div>								
								<div class="form-group {{ ($errors->has('contract_no')) ? 'has-error' : '' }}">
									<label >@lang('basic.contract_no')</label>
									<input class="form-control"  name="contract_no" type="text" maxlength="20" value="{{ $contract->contract_no }}" required />
									{!! ($errors->has('contract_no') ? $errors->first('contract_no', '<p class="text-danger">:message</p>') : '') !!}
								</div>
								<div class="form-group datum {{ ($errors->has('date')) ? 'has-error' : '' }}">
									<label >@lang('basic.contract_date')</label>
									<input class="form-control" name="date" type="date" maxlength="20" value="{{ $contract->date }}" required />
									{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
								</div>
								<div class="form-group {{ ($errors->has('duration')) ? 'has-error' : '' }}">
									<label >@lang('basic.duration_contract') [@lang('basic.months')]</label>
									<input class="form-control" name="duration" type="number" min="1" max="60" value="{{ $contract->duration }}" required />
									{!! ($errors->has('duration') ? $errors->first('duration', '<p class="text-danger">:message</p>') : '') !!}
								</div>
								<div class="form-group {{ ($errors->has('invoice_no')) ? 'has-error' : '' }}">
									<label >@lang('basic.invoice_no')</label>
									<input class="form-control" name="invoice_no" maxlength="20"  type="text" value="{{ $contract->invoice_no }}" required />
									{!! ($errors->has('invoice_no') ? $errors->first('invoice_no', '<p class="text-danger">:message</p>') : '') !!}
								</div>
								<div class="form-group datum {{ ($errors->has('invoice_date')) ? 'has-error' : '' }}">
									<label >@lang('basic.invoice_date')</label>
									<input class="form-control" name="invoice_date" type="date" maxlength="20" value="{{ $contract->invoice_date }}" required />
									{!! ($errors->has('invoice_date') ? $errors->first('invoice_date', '<p class="text-danger">:message</p>') : '') !!}
								</div>
								<div class="form-group ">
									<label >Jamstvo  - Bianco zadužnica</label>
									<p class="contract_name"><input class="form-control price" name="debenture_amount" type="number" step="0.01" min="0" value="{{ $contract->debenture_amount }}" /><span class="float_right">Kn</span></p>
								</div>
								<div class="form-group">
									<label >Cijena otiska formata A3 jednaka je cijeni dva otiska formata A4</label>
									<label for="a3_1">Da</label>
									<input class="test" name="a3" type="radio" id="a3_1" value="1" {!! $contract->a3 == 1 ? 'checked' : '' !!} />
									<label for="a3_0">Ne</label>
									<input class="test" name="a3" type="radio" id="a3_0" value="0" {!! $contract->a3 == 0 ? 'checked' : '' !!}  />
								</div>
								<div class="form-group">
									<label >Testiranje uređaja</label>
									<label for="test1">Da</label>
									<input class="test" name="test" type="radio" id="test1" value="1" {!! $contract->package_prints_bw || $contract->package_prints_c ? 'checked' : '' !!}/>
									<label for="test0" >Ne</label>
									<input class="test" name="test" type="radio" id="test0" value="0" {!! ! $contract->package_prints_bw && ! $contract->package_prints_c ? 'checked' : '' !!} />
								</div>
								<div class="test_group " style="display:{!! $contract->package_prints_bw || $contract->package_prints_c ? 'block' : 'none' !!}"">
									<label >Uključeno otisaka u periodu testiranja</label>
									<p class="contract_name"><span>c/b:</span><input class="form-control" name="package_prints_bw" type="number" min="0" value="{{  $contract->package_prints_bw }}"  /></p>
									<p class="contract_name"><span>boja:</span><input class="form-control" name="package_prints_c" type="number" min="0" value="{{  $contract->package_prints_c }}"  /></p>	
								</div>
								<p class="contract_title">Predmet ugovora</p>
								<div class="subject_group">
									@foreach ( $contract->hasSubjects as $key => $subject)
										<input class="form-control" name="subject_id[]" type="hidden" value="{{ $subject->id }}" />
										<div class="subject">
											<p class="subject_no">Uređaj <span>{{ $key + 1 }}</span></p>
											<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
												<label >@lang('basic.subject_name')</label>
												<input class="form-control" name="subject_name[]" type="text" maxlength="100" value="{{ $subject->name }}" required />
												{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
											</div>
											<div class="form-group {{ ($errors->has('serial_no')) ? 'has-error' : '' }}">
												<label >@lang('basic.serial_no')</label>
												<input class="form-control" name="subject_serial_no[]" type="text" maxlength="50" value="{{ $subject->serial_no }}" required />
												{!! ($errors->has('serial_no') ? $errors->first('serial_no', '<p class="text-danger">:message</p>') : '') !!}
											</div>
											<div class="form-group ">
												<label >Početno stanje brojčanika na dan montaže</label>
												<p class="contract_name"><span>c/b:</span><input class="form-control" name="subject_counter_bw[]" type="number" min="0" value="{{ $subject->counter_bw }}" required /></p>
												<p class="contract_name"><span>boja:</span><input class="form-control" name="subject_counter_c[]" type="number" min="0" value="{{ $subject->counter_c }}" required /></p>			
											</div>
											<div class="form-group {{ ($errors->has('location_id')) ? 'has-error' : '' }}">
												<label>@lang('basic.subject_location')</label>
												<select class="form-control location_id" name="subject_location_id[]" value="{{ $subject->location_id }}" required >
													@foreach ($contract->customer->hasLocations as $location)
														<option value="{{ $location->id }}" {!! $subject->location_id == $location->id ? 'selected' : '' !!}>{{ $location->address . ', '. $location->city }}</option>
													@endforeach
												</select>
												{!! ($errors->has('location_id') ? $errors->first('location_id', '<p class="text-danger">:message</p>') : '') !!}
												<span class="add_location cursor">Unesi novu lokaciju</span><br>
											</div>
											<div class="location_group">
												<div class="form-group {{ ($errors->has('location_address')) ? 'has-error' : '' }}">
													<input class="form-control" placeholder="{{ __('basic.address')}}" name="subject_location_address[]" type="text" maxlength="100" value="{{ old('location_address') }}"  />
													{!! ($errors->has('location_address') ? $errors->first('location_address', '<p class="text-danger">:message</p>') : '') !!}
												</div>
												<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
													<input class="form-control" placeholder="{{ __('basic.city')}}" name="subject_location_city[]" type="text" maxlength="50" value="{{ old('city') }}"  />
													{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
												</div>
											</div>
											<div class="form-group">
												<label>Mjesečni paušal</label>
												<p class="contract_name"><input class="form-control price" name="subject_flat_rate[]" type="number" min="0" step="0.01" value="{{ $subject->flat_rate }}" /><span class="float_right">Kn</span></p>
											</div>
											<div class="form-group ">
												<label >Ispisni paket uključen u mjesečni paušal</label>
												<p class="contract_name"><span>c/b:</span><input class="form-control" name="subject_no_prints_bw[]" type="number" min="0" value="{{ $subject->no_prints_bw }}"  /></p>
												<p class="contract_name"><span>boja:</span><input class="form-control" name="subject_no_prints_c[]" type="number" min="0" value="{{ $subject->no_prints_c }}"  /></p>	
											</div>
											<div class="form-group ">
												<label >Cijena otiska A4</label>
												<p class="contract_name"><span>c/b:</span><input class="form-control price_col" name="subject_price_a4_bw[]" type="number" step="0.01" min="0" value="{{ $subject->price_a4_bw }}" required /><span class="float_right">Kn</span></p>
												<p class="contract_name"><span>boja:</span><input class="form-control price_col" name="subject_price_a4_c[]" type="number" step="0.01" min="0" value="{{ $subject->price_a4_c }}" required /><span class="float_right">Kn</span></p>	
											</div>
										</div>
									@endforeach
								</div>
								<span class="add_subject cursor">Unesi novi uređaj</span>
							@csrf
							@method('PUT')
							<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
						</fieldset>
					</form>
				</div>
				<script>
					addCustomer();
					addLocation();
					customerChange();
					customerForm();
					addSubject();

					function addCustomer() {
						$('.add_customer').on('click', function () {
							$('.customer_group').toggle();
							if( $('.customer_group').is(':visible')) {
								$('input[name^="customer"]').prop('required',true);
							} else {
								$('input[name^="customer"]').prop('required',false);
							}
						});
					}

					function addLocation() {
						$('.add_location').on('click', function () {
							$('.location_group').toggle();
							if( $('.location_group').is(':visible')) {
								$('input[name^="location"]').prop('required',true);
								$('select[name^="location"]').prop('required',true);
							} else {
								$('input[name^="location"]').prop('required',false);
								$('select[name^="location"]').prop('required',false);
							}
						});
					}

					function customerForm() {
						$('.customer_form').on('submit', function (e) {
							e.preventDefault();
							url = $( this ).attr('action');
							form_data = $(this).serialize(); 

							$.ajaxSetup({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
							$.ajax({
								url: url,
								type: "post",
								data: form_data,
								beforeSend: function(){
									$('body').prepend('<div id="loader"></div>');
								},
								success: function( data ) {
									console.log(data);
									$('#loader').remove();
									$('.customer_group').hide();
									$('.select_customer').load(location.href + ' .select_customer select',function(){										
										$('#customer_id').find('option[value='+data.customer_id+']').attr('selected', "selected");
									});
									var option = '';
									$.each(data.locations, function( index, location ) {
										option += '<option value="'+ location.id +'">'+location.address+ ', '+ location.city+'</option>'
									});
									$('.location_id').append(option);

								}, 
								error: function(xhr,textStatus,thrownError) {
									console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError);                            
								}
							});
						});
						$('.close_customer').on('click', function () {
							$('.customer_group').hide();
						});
					}

					function customerChange() {
						$('#customer_id').change(function (e) { 
							var customer_id = $(this).val();
							console.log("customer_id "+customer_id);
							url = location.origin + '/getCustomerLocation';
							$.ajax({
								url: url,
								type: "get",
								data:  { customer_id: customer_id },
								success: function( locations ) {
									$('.location_id').find('option').remove();
									var option = '';
									$.each(locations, function( index, location ) {
										option += '<option value="'+ location.id +'">'+location.address+ ', '+ location.city+'</option>'
									});
									$('.location_id').append(option);
								},
								error: function(jqXhr, json, errorThrown) {
									console.log(jqXhr);
									console.log(json);
									console.log(errorThrown);
								}
							}); 
						});
					}

					function addSubject() {
						$('.add_subject').on('click', function () {
							$( ".subject" ).first().clone().appendTo( ".subject_group" );
							$( ".subject" ).last().find('.subject_no').find('span').text($('.subject').length);
							$( ".subject" ).last().find('.add_location').on('click', function () {
								$('.location_group').toggle();
								if( $('.location_group').is(':visible')) {
									$('input[name^="location"]').prop('required',true);
									$('select[name^="location"]').prop('required',true);
								} else {
									$('input[name^="location"]').prop('required',false);
									$('select[name^="location"]').prop('required',false);
								}
							});
						});
					}

					function test () {
						$('.test').on('click', function () {
							if( $( this ).val() == 1 ) { 
								$('.test_group').show();
								$('input[name^="package_prints"]').prop('required',true);
							} else {
								$('.test_group').hide();
								$('input[name^="package_prints"]').prop('required',false);
							}
						});
					}
						
					$('input[name^=subject_location_address]').on('change',function(){
						if( $(this).val() != '') {
							$(this).parent().parent().prev('.form-group').find('select').prop('required',false);
						} else {
							$(this).parent().parent().prev('.form-group').find('select').prop('required',true);
						}
					});
				</script>
			</main>
		</section>
	</main>
</div>
@stop