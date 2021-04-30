<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_subject')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('contract_subjects.edit', $contract_subject->id ) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('contract_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.contract')</label>
				<select class="form-control" name="contract_id" value="{{ $contract_subject->id }}" >
					<option value="" selected disabled></option>
					@foreach ($contracts as $contract)
						<option value="{{ $contract->id }}" {!! $contract_subject->contract_id == $contract->id ? 'selected' : '' !!}>Ugovor {{ $contract->template->name }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('contract_id') ? $errors->first('contract_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('location_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.subject_location')</label>
				<select class="form-control" name="location_id" value="{{ old('location_id') }}" >
					<option value="" selected disabled></option>
					@foreach ($customer_locations as $customer_location)
						<option value="{{ $customer_location->id }}" {!! $contract_subject->location_id == $customer_location->id ? 'selected' : '' !!} >{{ $customer_location->customer->name . ' - ' . $customer_location->address . ', ' . $customer_location->city  }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('location_id') ? $errors->first('location_id', '<p class="text-danger">:message</p>') : '') !!}
				<span class="add_location cursor">Unesi novu lokaciju</span><br>
				<span class="add_customer cursor">Unesi novog naručitelja</span>
			</div>
			<div class="location_group">
				<div class="form-group {{ ($errors->has('customer_id')) ? 'has-error' : '' }}">
					<label>@lang('basic.customer')</label>
					<select class="form-control" name="location_customer_id" value="{{ old('customer_id') }}" >
						<option value="" selected disabled></option>
						@foreach ($customers as $customer)
								<option value="{{ $customer->id }}">{{ $customer->name }}</option>
						@endforeach	
					</select>
					{!! ($errors->has('customer_id') ? $errors->first('customer_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('location_address')) ? 'has-error' : '' }}">
					<input class="form-control" placeholder="{{ __('basic.address')}}" name="location_address" type="text" maxlength="100" value="{{ old('location_address') }}" required />
					{!! ($errors->has('location_address') ? $errors->first('location_address', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
					<input class="form-control" placeholder="{{ __('basic.city')}}" name="location_city" type="text" maxlength="50" value="{{ old('city') }}" required />
					{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			</div>
			<div class="customer_group">
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
			</div>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label >@lang('basic.subject_name')</label>
				<input class="form-control" name="name" type="text" maxlength="100" value="{{  $contract_subject->name }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('serial_no')) ? 'has-error' : '' }}">
				<label >@lang('basic.serial_no')</label>
				<input class="form-control" name="serial_no" type="text" maxlength="50" value="{{  $contract_subject->serial_no }}" required />
				{!! ($errors->has('serial_no') ? $errors->first('serial_no', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group ">
				<label >Početno stanje brojčanika na dan montaže</label>
				<p class="contract_name"><span>c/b:</span><input class="form-control" name="counter_bw" type="number" min="0" value="{{  $contract_subject->counter_bw }}" required /></p>
				<p class="contract_name"><span>boja:</span><input class="form-control" name="counter_c" type="number" min="0" value="{{  $contract_subject->counter_c }}" required /></p>			
			</div>
			<div class="form-group">
				<label >Testiranje uređaja</label>
				<label for="test1">Da</label>
				<input class="test" name="test" type="radio" id="test1" value="1"  />
				<label for="test0">Ne</label>
				<input class="test" name="test" type="radio" id="test0" value="0"  />
			</div>
			<div class="test_group ">
				<label >Uključeno otisaka u periodu testiranja</label>
				<p class="contract_name"><span>c/b:</span><input class="form-control" name="package_prints_bw" type="number" min="0" value="{{  $contract_subject->package_prints_bw }}"  /></p>
				<p class="contract_name"><span>boja:</span><input class="form-control" name="package_prints_c" type="number" min="0" value="{{  $contract_subject->package_prints_c }}"  /></p>	
			</div>
			<div class="form-group">
				<label>Mjesečni paušal</label>
				<p class="contract_name"><input class="form-control price" name="flat_rate" type="number" min="0" step="0.01" value="{{  $contract_subject->flat_rate }}" /><span class="float_right">Kn</span></p>
			</div>
			<div class="form-group ">
				<label >Ispisni paket uključen u mjesečni paušal</label>
				<p class="contract_name"><span>c/b:</span><input class="form-control" name="no_prints_bw" type="number" min="0" value="{{  $contract_subject->no_prints_bw }}"  /></p>
				<p class="contract_name"><span>boja:</span><input class="form-control" name="no_prints_c" type="number" min="0" value="{{  $contract_subject->no_prints_c }}"  /></p>	
			</div>
			<div class="form-group ">
				<label >Cijena otiska A4</label>
				<p class="contract_name"><span>c/b:</span><input class="form-control price_col" name="price_a4_bw" type="number" step="0.01" min="0" value="{{  $contract_subject->price_a4_bw }}" required /><span class="float_right">Kn</span></p>
				<p class="contract_name"><span>boja:</span><input class="form-control price_col" name="price_a4_c" type="number" step="0.01" min="0" value="{{  $contract_subject->price_a4_c }}" required /><span class="float_right">Kn</span></p>	
			</div>
			<div class="form-group ">
				<label >Jamstvo</label>
				
			</div>
			<div class="form-group ">
				<label >Bianco zadužnica</label>
				<p class="contract_name"><input class="form-control price" name="debenture_amount" type="number" step="0.01" min="0" value="{{  $contract_subject->debenture_amount }}" /><span class="float_right">Kn</span></p>
			</div>
			@csrf
			@method('PUT')
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<script>
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
	$('.add_customer').on('click', function () {
		$('.customer_group').toggle();
		if( $('.customer_group').is(':visible')) {
			$('input[name^="customer"]').prop('required',true);
		} else {
			$('input[name^="customer"]').prop('required',false);
		}
	});
	$('.test').on('click', function () {
		if( $( this ).val() == 1 ) { 
			$('.test_group').show();
			$('input[name^="package_prints"]').prop('required',true);
		} else {
			$('.test_group').hide();
			$('input[name^="package_prints"]').prop('required',false);
		}
	});
</script>