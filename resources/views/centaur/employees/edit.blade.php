@php
	$stazY = 0;
	$stazM = 0;
	$stazD = 0;
	if($employee->years_service) {
		$staz = explode('-',$employee->years_service);
		$stazY = $staz[0];
		$stazM = $staz[1];
		$stazD = $staz[2];
	}
@endphp
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_employee')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employees.update', $employee->id ) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.user')</label>
				<select class="form-control" name="user_id" required>
					<option value="{{ $employee->user_id }}" >{{ $employee->user['last_name'] . ' ' . $employee->user['first_name'] }}</option>
				</select>
				{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('erp_id'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>ERP ID</label>
				<input class="form-control " type="text" name="erp_id" value="{{ $employee->erp_id }}" maxlength="10" >
				{!! ($errors->has('color') ? $errors->first('color', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('color'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.color') </label>
				<input class="form-control color"  type="color" name="color" value="{!! $employee->color ? $employee->color : '#ffffff' !!}" >
				{!! ($errors->has('color') ? $errors->first('color', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_day')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_day')</label>
				<input class="form-control" placeholder="{{ __('basic.b_day')}}" name="b_day" type="date" value="{{ $employee->b_day }}" />
				{!! ($errors->has('b_day') ? $errors->first('b_day', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_place')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_place')</label>
				<input class="form-control" placeholder="{{ __('basic.b_place')}}" name="b_place" maxlength="50" type="text" value="{{ $employee->b_place }}"  />
				{!! ($errors->has('b_place') ? $errors->first('b_place', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('father_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.father_name')</label>
				<input class="form-control" placeholder="{{ __('basic.father_name')}}" name="father_name" maxlength="20" type="text" value="{{ $employee->father_name }}"  />
				{!! ($errors->has('father_name') ? $errors->first('father_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mather_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.mather_name')</label>
				<input class="form-control" placeholder="{{ __('basic.mather_name')}}" name="mather_name" maxlength="20" type="text" value="{{ $employee->mather_name }}"  />
				{!! ($errors->has('mather_name') ? $errors->first('mather_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('maiden_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.maiden_name')</label>
				<input class="form-control" name="maiden_name" type="text" value="{{ $employee->maiden_name }}" maxlength="20" />
				{!! ($errors->has('maiden_name') ? $errors->first('maiden_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
				<label>@lang('basic.oib')</label>
				<input class="form-control" placeholder="{{ __('basic.oib')}}" name="oib" type="text" maxlength="20" value="{{ strval($employee->oib) }}" required />
				{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi')</label>
				<input class="form-control" placeholder="{{ __('basic.oi')}}" name="oi" type="text" maxlength="20" value="{{ $employee->oi }}" />
				{!! ($errors->has('oi') ? $errors->first('oi', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi_expiry')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi_expiry')</label>
				<input class="form-control" placeholder="{{ __('basic.oi_expiry')}}" name="oi_expiry" type="date" value="{{ $employee->oi_expiry }}"  />
				{!! ($errors->has('oi_expiry') ? $errors->first('oi_expiry', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.mobile')</label>
				<input class="form-control" placeholder="{{ __('basic.mobile')}}" name="mobile" maxlength="50" type="text" value="{{ $employee->mobile }}"  />
				{!! ($errors->has('mobile') ? $errors->first('mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_mobile')</label>
				<input class="form-control" placeholder="{{ __('basic.priv_mobile')}}" name="priv_mobile" maxlength="50" type="text" value="{{ $employee->priv_mobile }}" />
				{!! ($errors->has('priv_mobile') ? $errors->first('priv_mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
				<label>e-mail</label>
				<input class="form-control" placeholder="e-mail" name="email" type="email" maxlength="50" value="{{$employee->email }}" required />
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_email')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_email')</label>
				<input class="form-control" placeholder="Privatan e-mail" name="priv_email" maxlength="50" type="email" value="{{ $employee->priv_email }}"  />
				{!! ($errors->has('priv_email') ? $errors->first('priv_email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_adresa')) ? 'has-error' : '' }}">
				<label>Adresa prebivališta</label>
				<input class="form-control" placeholder="" name="prebiv_adresa" type="text" maxlength="50" value="{{ $employee->prebiv_adresa }}"  />
				{!! ($errors->has('prebiv_adresa') ? $errors->first('prebiv_adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_grad')) ? 'has-error' : '' }}">
				<label>Grad prebivališta</label>
				<input class="form-control" placeholder="" name="prebiv_grad" type="text" maxlength="50" value="{{ $employee->prebiv_grad }}"  />
				{!! ($errors->has('prebiv_grad') ? $errors->first('prebiv_grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('borav_adresa')) ? 'has-error' : '' }}">
				<label>Adresa boravišta</label>
				<input class="form-control" placeholder="" name="borav_adresa" type="text" maxlength="50" value="{{ $employee->borav_adresa }}"  />
				{!! ($errors->has('borav_adresa') ? $errors->first('borav_adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('borav_grad')) ? 'has-error' : '' }}">
				<label>Grad boravišta</label>
				<input class="form-control" placeholder="" name="borav_grad" type="text" maxlength="50" value="{{ $employee->borav_grad }}"  />
				{!! ($errors->has('borav_grad') ? $errors->first('borav_grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label>@lang('basic.metier')</label>
				<input name="title" type="text" class="form-control" value="{{ $employee->title }}" maxlength="50"  >
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('qualifications')) ? 'has-error' : '' }}">
				<label>@lang('basic.qualifications')</label>
				<input name="qualifications" type="text" class="form-control" value="{{ $employee->qualifications }}" maxlength="20"  >
				{!! ($errors->has('qualifications') ? $errors->first('qualifications', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('marital'))  ? 'has-error' : '' }}">
				<label>@lang('basic.marital')</label>
				<select class="form-control" name="marital" value="{{ $employee->marital }}"   >
					<option selected="selected" value=""></option>
					<option {!! $employee->marital == 'yes' ? 'selected' : '' !!}  value="yes" >@lang('basic.married')</option>
					<option {!! $employee->marital == 'no' ? 'selected' : '' !!}  value="no" >@lang('basic.not_married')</option>
				</select>
				{!! ($errors->has('bracno_stanje') ? $errors->first('bracno_stanje', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('work_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.work')</label>
				<select class="form-control" name="work_id" value="{{ $employee->work_id }}" required >
					<option selected="selected" value=""></option>
					@foreach($works as $work)
						<option name="work_id" value="{{ $work->id }}" {!! $employee->work_id == $work->id ? 'selected' : '' !!} >{{ $work->name . ' - '. $work->department['name']  }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('work_id') ? $errors->first('work_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('department_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.department')</label>
				<select class="form-control" name="department_id[]" value="{{ old('department_id') }}" rows="6" required multiple >
					<option {!! count($employee->hasEmployeeDepartmen) ==0 ? 'selected="selected"' : '' !!} disabled></option>
					@foreach($departments as $department)
						<option name="department_id" value="{{ $department->id }}" {!! $employee->hasEmployeeDepartmen->where('department_id',$department->id)->first() ? 'selected' : '' !!} >{{ $department->name }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('superior_id'))  ? 'has-error' : '' }}">
				<span><b>Nadređeni djelatnik:</b></span>
				<select class="form-control" name="superior_id"  >
					<option disabled selected></option>
					@foreach($employees as $djelatnik)
						<option name="superior_id" value="{{ $djelatnik->id }}" {!! $employee->superior_id == $djelatnik->id ? 'selected' : '' !!} >{{ $djelatnik->user['last_name'] . ' '. $djelatnik->user['first_name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('superior_id') ? $errors->first('superior_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			@if(Sentinel::inRole('view_efc') || Sentinel::inRole('uprava') )
				<div class="form-group {{ ($errors->has('effective_cost'))  ? 'has-error' : '' }}">
					<span><b>Efektivna cijena sata:</b></span>
					<input class="form-control" name="effective_cost" type="number" step="0.01" value="{{ $employee->effective_cost }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
					title="This must be a number with up to 2 decimal places" />
					{!! ($errors->has('effective_cost') ? $errors->first('effective_cost', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('brutto'))  ? 'has-error' : '' }}">
					<span><b>Brutto godišnja plaća:</b></span>
					<input class="form-control" name="brutto" type="number" step="0.01" value="{{ $employee->brutto }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
					title="This must be a number with up to 2 decimal places" />
					{!! ($errors->has('brutto') ? $errors->first('brutto', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@endif
			<div class="form-group {{ ($errors->has('reg_date')) ? 'has-error' : '' }}">
				<label>@lang('basic.reg_date')</label>
				<input class="form-control" placeholder="{{ __('basic.reg_date')}}" name="reg_date" type="date" value="{{ $employee->reg_date }}" />
				{!! ($errors->has('reg_date') ? $errors->first('reg_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('checkout')) ? 'has-error' : '' }}">
				<label>@lang('basic.checkout')</label>
				<input class="form-control" placeholder="{{ __('basic.checkout')}}" name="checkout" type="date" value="{{ $employee->checkout }}"  />
				{!! ($errors->has('checkout') ? $errors->first('checkout', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('probation')) ? 'has-error' : '' }}">
				<label>@lang('basic.probation')</label>
				<input class="form-control" placeholder="{{ __('basic.probation')}}" name="probation" type="number" step="1" value="{{ $employee->probation }}" pattern="[0-9]" />
				{!! ($errors->has('probation') ? $errors->first('probation', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('years_service'))  ? 'has-error' : '' }}">
				<label>Staž kod prošlog poslodavca (godina-mjeseci-dana):</label><br>
				<input name="stazY" type="text" class="staz" value="{{ $stazY }}" maxlength="2" >-
				<input name="stazM" type="text" class="staz" value="{{ $stazM }}" maxlength="2" >-
				<input name="stazD" type="text" class="staz" value="{{ $stazD }}" maxlength="2" >
				{!! ($errors->has('years_service') ? $errors->first('years_service', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {!! ($errors->has('termination_service'))  ? 'has-error' : '' !!}">
				<input type="checkbox" name="termination_service" value="DA" {!! $employee->termination_service  ? 'checked' : '' !!} > Prekid radnog odnosa više od 8 dana
					{!! ($errors->has('termination_service') ? $errors->first('termination_service', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<input type="checkbox" name="first_job" {!! $employee->first_job  ? 'checked' : '' !!} > Prvo zaposlenje
			</div>
			<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.comment') </label>
				<textarea class="form-control" name="comment" maxlength="65535" >{{ $employee->comment }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('lijecn_pregled')) ? 'has-error' : '' }}">
				<label>@lang('basic.lijecn_pregled')</label>
				<input class="form-control" placeholder="{{ __('basic.lijecn_pregled')}}" name="lijecn_pregled" type="date" value="{{ $employee->lijecn_pregled }}" required />
				{!! ($errors->has('lijecn_pregled') ? $errors->first('lijecn_pregled', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('znr')) ? 'has-error' : '' }}">
				<label>@lang('basic.znr')</label>
				<input class="form-control" placeholder="{{ __('basic.znr')}}" name="znr" type="date" value="{{ $employee->znr }}" required />
				{!! ($errors->has('znr') ? $errors->first('znr', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('size')) ? 'has-error' : '' }}">
				<label>@lang('basic.size')</label>
				<input name="size" type="text" class="form-control" maxlength="10" value="{{ $employee->size }}"   >
				{!! ($errors->has('size') ? $errors->first('size', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('shoe_size')) ? 'has-error' : '' }}">
				<label>@lang('basic.shoe_size')</label>
				<input name="shoe_size" type="text" class="form-control" maxlength="10" value="{{ $employee->shoe_size }}"   >
				{!! ($errors->has('shoe_size') ? $errors->first('shoe_size', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('days_off')) ? 'has-error' : '' }}">
				<label>Obračun prekovremenih kao: </label>
				<select class="form-control" name="days_off" value="{{ old('days_off') }}">
					<option value="1" {!! $employee->days_off == 1 ? 'selected' : '' !!}  >Slobodni dani</option>
					<option value="0" {!! $employee->days_off == 0 ? 'selected' : '' !!} >Isplata</option>
				</select>
			</div>
			<div class="form-group">
				<input type="checkbox" name="stranger" value="1" id="stranger" {!! $employee->stranger == 1 ? 'checked' : '' !!} > <label for="stranger">Djelatnik je stranac</label>
			</div>
			<div class="form-group"  id="dozvola">
				<label>Datum isteka dozvole boravka u RH: </label>
				<input name="permission_date" class="form-control" type="date" value="{{ $employee->permission_date }}" >
			</div>

			@php
				$abs_day = 0;
				$abs_year = date('Y');
				if( $employee->abs_days) {
					$abs_days = unserialize( $employee->abs_days);
				}				
			@endphp
			<div class="form-group group_abs_days {{ ($errors->has('abs_days'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.abs_days') <span class="add_new">@lang('basic.add')</span></label>
				@if(isset($abs_days))
					@foreach ($abs_days as $year => $days)
						<div class="group_abs">
							<input class="form-control day_go" type="number" step="1" maxlength="2" name="abs_days[]" value="{{ $days }}" />
							<span class="float_l span_day_go">@lang('absence.days')  @lang('basic.for_year')</span>
							<input class="form-control day_go" type="number" step="1" maxlength="4" placeholder="{{ __('basic.year') }}" name="abs_year[]" value="{{ $year }}" />
							<span class="remove"><i class="far fa-trash-alt"></i></span>
						</div>
					@endforeach
				@else
					<div class="group_abs">
						<input class="form-control day_go" type="number" step="1" maxlength="2" name="abs_days[]" value="{!! old('abs_days') ?  old('abs_days')  : 0 !!}" />
						<span class="float_l span_day_go">@lang('absence.days')  @lang('basic.for_year')</span>
						<input class="form-control day_go" type="number" step="1" maxlength="4" placeholder="{{ __('basic.year') }}" name="abs_year[]" value="{!! old('abs_year') ? old('abs_year')  : date('Y')!!}" />
						<span class="remove"><i class="far fa-trash-alt"></i></span>
					</div>
				@endif				
				{!! ($errors->has('abs_days') ? $errors->first('abs_days', '<p class="text-danger">:message</p>') : '') !!}
			</div>		
			@if(in_array('Kampanje', $moduli) && count($campaigns)>0)
				<div class="form-group {{ ($errors->has('campaign_id')) ? 'has-error' : '' }}">
					<label>@lang('basic.campaigns')</label>
					<select class="form-control" name="campaign_id[]" multiple >
						<option value=""  ></option>
						@foreach($campaigns as $campaign)	
							<option value="{{ $campaign->id}}" {!! $campaignRecipients->where('campaign_id', $campaign->id)->first() ? 'selected' : '' !!}>{{ $campaign->name }}</option>
						@endforeach
					</select>
					{!! ($errors->has('campaign_id') ? $errors->first('campaign_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@endif	
			<div class="form-group">
				<label >@lang('absence.email_send')</label>
				<span><input type="radio" name="send_email" value="DA"  /> @lang('basic.send_mail') </span>
				<span><input type="radio" name="send_email" value="NE" checked /> @lang('basic.dont_send_mail')</span>
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$('.add_new').click(function(){
		$('.group_abs_days').append('<div class="group_abs"><input class="form-control day_go" type="number" step="1" maxlength="2" name="abs_days[]" value="0" /><span class="float_l span_day_go">@lang('absence.days')  @lang('basic.for_year')</span><input class="form-control day_go" type="number" step="1" maxlength="4" placeholder="{{ __('basic.year') }}" name="abs_year[]" /><span class="remove"><i class="far fa-trash-alt"></i></span></div>');
		$('.remove').click(function(){
			$(this).parent().remove();
			console.log("remove");
		});
	});
	
    $.getScript( '/../js/validate.js'); 
</script>

