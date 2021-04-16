<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_employee')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employees.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.user')</label>
				<select class="form-control" name="user_id" required>
					<option value="" disabled selected ></option>
					@foreach($users as $user)					
						@if( ! $employees->where('user_id', $user->id)->first() )
							<option value="{{ $user->id}}" {!! isset($user1) && $user1->id ==  $user->id ? 'selected' : '' !!}>{{ $user->last_name . ' ' . $user->first_name }}</option>
						@endif					
					@endforeach
				</select>
				{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('color'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.color') </label>
				<input class="form-control color" type="color" name="color" value="{!! old('color') ? old('color') : '#ffffff'  !!}" >
				{!! ($errors->has('color') ? $errors->first('color', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_day')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_day')</label>
				<input class="form-control" name="b_day" type="date" value="{{ old('b_day') }}"  />
				{!! ($errors->has('b_day') ? $errors->first('b_day', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_place')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_place')</label>
				<input class="form-control"  name="b_place" type="text" value="{{ old('b_place') }}" maxlength="50" />
				{!! ($errors->has('b_place') ? $errors->first('b_place', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('father_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.father_name')</label>
				<input class="form-control" name="father_name" type="text" value="{{ old('father_name') }}" maxlength="20" />
				{!! ($errors->has('father_name') ? $errors->first('father_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mather_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.mather_name')</label>
				<input class="form-control" name="mather_name" type="text" value="{{ old('mather_name') }}" maxlength="20" />
				{!! ($errors->has('mather_name') ? $errors->first('mather_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('maiden_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.maiden_name')</label>
				<input class="form-control" name="maiden_name" type="text" value="{{ old('maiden_name') }}" maxlength="20" />
				{!! ($errors->has('maiden_name') ? $errors->first('maiden_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
				<label>@lang('basic.oib')</label>
				<input class="form-control" name="oib" type="number" step="1" maxlength="20" value="{{ old('oib') }}" required />
				{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi')</label>
				<input class="form-control" name="oi" type="number" step="1" maxlength="20" value="{{ old('oi') }}"  />
				{!! ($errors->has('oi') ? $errors->first('oi', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi_expiry')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi_expiry')</label>
				<input class="form-control" name="oi_expiry" type="date" value="{{ old('oi_expiry') }}"  />
				{!! ($errors->has('oi_expiry') ? $errors->first('oi_expiry', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.mobile')</label>
				<input class="form-control" name="mobile" type="text" maxlength="50" value="{{ old('mobile') }}"  />
				{!! ($errors->has('mobile') ? $errors->first('mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_mobile')</label>
				<input class="form-control" name="priv_mobile" type="text" maxlength="50" value="{{ old('priv_mobile') }}" />
				{!! ($errors->has('priv_mobile') ? $errors->first('priv_mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
				<label>e-mail</label>
				<input class="form-control" name="email" type="email" maxlength="50" value="{!! isset($user1) ? $user1->email : old('email') !!}" required />
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_email')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_email')</label>
				<input class="form-control" name="priv_email" type="email" maxlength="50" value="{{ old('priv_email') }}"  />
				{!! ($errors->has('priv_email') ? $errors->first('priv_email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_adresa')) ? 'has-error' : '' }}">
				<label>Adresa prebivališta</label>
				<input class="form-control"name="prebiv_adresa" type="text" maxlength="50" value="{{ old('prebiv_adresa') }}"  />
				{!! ($errors->has('prebiv_adresa') ? $errors->first('prebiv_adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_grad')) ? 'has-error' : '' }}">
				<label>Grad prebivališta</label>
				<input class="form-control" name="prebiv_grad" type="text" maxlength="50" value="{{ old('prebiv_grad') }}"  />
				{!! ($errors->has('prebiv_grad') ? $errors->first('prebiv_grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('borav_adresa')) ? 'has-error' : '' }}">
				<label>Adresa boravišta</label>
				<input class="form-control" name="borav_adresa" type="text" maxlength="50" value="{{ old('borav_adresa') }}"  />
				{!! ($errors->has('borav_adresa') ? $errors->first('borav_adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('borav_grad')) ? 'has-error' : '' }}">
				<label>Grad boravišta</label>
				<input class="form-control" name="borav_grad" type="text" maxlength="50" value="{{ old('borav_grad') }}"  />
				{!! ($errors->has('borav_grad') ? $errors->first('borav_grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label>@lang('basic.metier')</label>
				<input name="title" type="text" class="form-control" maxlength="50" value="{{ old('title') }}"   >
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('qualifications')) ? 'has-error' : '' }}">
				<label>@lang('basic.qualifications')</label>
				<input name="qualifications" type="text" class="form-control" maxlength="20" value="{{ old('qualifications') }}"   >
				{!! ($errors->has('qualifications') ? $errors->first('qualifications', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('marital'))  ? 'has-error' : '' }}">
				<label>@lang('basic.marital')</label>
				<select class="form-control" name="marital" value="{{ old('marital') }}"   >
					<option selected="selected" value=""></option>
					<option  value="yes">@lang('basic.married')</option>
					<option  value="no">@lang('basic.not_married')</option>
				</select>
				{!! ($errors->has('bracno_stanje') ? $errors->first('bracno_stanje', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('work_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.work')</label>
				<select class="form-control" name="work_id" value="{{ old('work_id') }}" required >
					<option selected="selected" disabled></option>
					@foreach($works as $work)
						<option name="work_id" value="{{ $work->id }}">{{ $work->name . ' - '. $work->department['name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('work_id') ? $errors->first('work_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('department_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.department')</label>
				<select class="form-control" name="department_id[]" value="{{ old('department_id') }}" rows="6" required multiple >
					<option selected="selected" disabled></option>
					@foreach($departments as $department)
						<option name="department_id" value="{{ $department->id }}">{{ $department->name . '[' .$department->level1 . ']' }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('superior_id'))  ? 'has-error' : '' }}">
				<span><b>Nadređeni djelatnik:</b></span>
				<select class="form-control" name="superior_id" >
					<option selected value="0"></option>
					@foreach($employees as $employee)
						<option name="superior_id" value="{{ $employee->id }}">{{ $employee->user['last_name'] . ' '. $employee->user['first_name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('superior_id') ? $errors->first('superior_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('effective_cost'))  ? 'has-error' : '' }}">
				<span><b>Efektivna cijena sata:</b></span>
				<input class="form-control" name="effective_cost" type="number" step="0.01" value="{{ old('effective_cost') }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
				title="This must be a number with up to 2 decimal places" />
				{!! ($errors->has('effective_cost') ? $errors->first('effective_cost', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('brutto')) ? 'has-error' : '' }}">
				<span><b>Brutto godišnja plaća:</b></span>
				<input class="form-control" name="brutto" type="number" step="0.01" value="{{ old('brutto') }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
				title="This must be a number with up to 2 decimal places" />
				{!! ($errors->has('brutto') ? $errors->first('brutto', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('reg_date')) ? 'has-error' : '' }}">
				<label>@lang('basic.reg_date')</label>
				<input class="form-control" placeholder="{{ __('basic.reg_date')}}" name="reg_date" type="date" value="{{ old('reg_date') }}" />
				{!! ($errors->has('reg_date') ? $errors->first('reg_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('probation')) ? 'has-error' : '' }}">
				<label>@lang('basic.probation')</label>
				<input class="form-control" placeholder="{{ __('basic.probation')}}" name="probation" type="number" step="1" value="{{ old('probation') }}" pattern="[0-9]" />
				{!! ($errors->has('probation') ? $errors->first('probation', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('years_service'))  ? 'has-error' : '' }}">
				<label>Staž kod prošlog poslodavca (godina-mjeseci-dana):</label><br>
				<input name="stazY" type="text" class="staz" value="0" maxlength="2" >-
				<input name="stazM" type="text" class="staz" value="0" maxlength="2" >-
				<input name="stazD" type="text" class="staz" value="0" maxlength="2" >
				{!! ($errors->has('years_service') ? $errors->first('years_service', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<input type="checkbox" name="termination_service" value="DA" > Prekid radnog odnosa više od 8 dana
			</div>
			<div class="form-group">
				<input type="checkbox" name="first_job" value="DA" > Prvo zaposlenje
			</div>
			<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.comment') </label>
				<textarea class="form-control" maxlength="65535" name="comment"></textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('lijecn_pregled')) ? 'has-error' : '' }}">
				<label>@lang('basic.lijecn_pregled')</label>
				<input class="form-control" placeholder="{{ __('basic.lijecn_pregled')}}" name="lijecn_pregled" type="date" value="{{ old('lijecn_pregled') }}" required />
				{!! ($errors->has('lijecn_pregled') ? $errors->first('lijecn_pregled', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('znr')) ? 'has-error' : '' }}">
				<label>@lang('basic.znr')</label>
				<input class="form-control" placeholder="{{ __('basic.znr')}}" name="znr" type="date" value="{{ old('znr') }}" required />
				{!! ($errors->has('znr') ? $errors->first('znr', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('size')) ? 'has-error' : '' }}">
				<label>@lang('basic.size')</label>
				<input name="size" type="text" class="form-control" maxlength="10" value="{{ old('size') }}"   >
				{!! ($errors->has('size') ? $errors->first('size', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('shoe_size')) ? 'has-error' : '' }}">
				<label>@lang('basic.shoe_size')</label>
				<input name="shoe_size" type="text" class="form-control" maxlength="10" value="{{ old('shoe_size') }}"   >
				{!! ($errors->has('shoe_size') ? $errors->first('shoe_size', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('days_off')) ? 'has-error' : '' }}">
				<label>Obračun prekovremenih kao: </label>
				<select class="form-control" name="days_off" value="{{ old('days_off')}}">
					<option value="1" selected >Slobodni dani</option>
					<option value="0">Isplata</option>
				</select>
			</div>
			<div class="form-group">
				<input type="checkbox" name="stranger" value="1" id="stranger" ><label for="stranger">Djelatnik je stranac</label>
			</div>
			<div class="form-group" id="dozvola">
				<label>Datum isteka dozvole boravka u RH: </label>
				<input name="permission_date" class="form-control" type="date">
			</div>
			<div class="form-group group_abs_days{{ ($errors->has('abs_days'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.abs_days') <span class="add_new">@lang('basic.add')</span></label>
				<div class="group_abs">
					<input class="form-control day_go" type="number" step="1" maxlength="2" name="abs_days[]" value="{!! old('abs_days') ?  old('abs_days')  : 0 !!}" />
					<span class="float_l span_day_go">@lang('absence.days')  @lang('basic.for_year')</span>
					<input class="form-control day_go" type="number" step="1" maxlength="4" placeholder="{{ __('basic.year') }}" name="abs_year[]" value="{!! old('abs_year') ? old('abs_year')  : date('Y')!!}" />
					<span class="remove"><i class="far fa-trash-alt"></i></span>
				</div>
				{!! ($errors->has('abs_days') ? $errors->first('abs_days', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			@if(in_array('Kampanje', $moduli) && count($campaigns)>0)
				<div class="form-group {{ ($errors->has('campaign_id')) ? 'has-error' : '' }}">
					<label>@lang('basic.campaigns')</label>
					<select class="form-control" name="campaign_id[]" multiple >
						<option value="" disabled selected ></option>
						@foreach($campaigns as $campaign)	
							<option value="{{ $campaign->id}}" >{{ $campaign->name }}</option>
						@endforeach
					</select>
					{!! ($errors->has('campaign_id') ? $errors->first('campaign_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@endif
			<div class="form-group">
				<label >@lang('absence.email_send')</label>
				<span><input type="radio" name="send_email" value="DA" checked /> @lang('basic.send_mail') </span>
				<span><input type="radio" name="send_email" value="NE" /> @lang('basic.dont_send_mail')</span>
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
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