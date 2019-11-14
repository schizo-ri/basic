
<?php
	$stazY = 0;
	$stazM = 0;
	$stazD = 0;
	if($employee->years_service) {
		$staz = $employee->years_service;
		$staz = explode('-',$employee->years_service);
		$stazY = $staz[0];
		$stazM = $staz[1];
		$stazD = $staz[2];
	}
?>

			
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_employee')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employees.update', $employee->id ) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.user')</label>
				<select class="form-control" name="user_id" required>
					<option value="" disabled selected ></option>
					@foreach($users as $user)
						<option value="{{ $user->id}}" {!! $user->id == $employee->user_id ? 'selected' : '' !!} >{{ $user->first_name . ' ' . $user->last_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_day')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_day')</label>
				<input class="form-control" placeholder="{{ __('basic.b_day')}}" name="b_day" type="date" value="{{ $employee->b_day }}" required />
				{!! ($errors->has('b_day') ? $errors->first('b_day', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_place')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_place')</label>
				<input class="form-control" placeholder="{{ __('basic.b_place')}}" name="b_place" type="text" value="{{ $employee->b_place }}" required />
				{!! ($errors->has('b_place') ? $errors->first('b_place', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('father_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.father_name')</label>
				<input class="form-control" placeholder="{{ __('basic.father_name')}}" name="father_name" type="text" value="{{ $employee->father_name }}"  />
				{!! ($errors->has('father_name') ? $errors->first('father_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mather_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.mather_name')</label>
				<input class="form-control" placeholder="{{ __('basic.mather_name')}}" name="mather_name" type="text" value="{{ $employee->mather_name }}"  />
				{!! ($errors->has('mather_name') ? $errors->first('mather_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
				<label>@lang('basic.oib')</label>
				<input class="form-control" placeholder="{{ __('basic.oib')}}" name="oib" type="text" value="{{ $employee->oib }}" required />
				{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi')</label>
				<input class="form-control" placeholder="{{ __('basic.oi')}}" name="oi" type="text" value="{{ $employee->oi }}" required />
				{!! ($errors->has('oi') ? $errors->first('oi', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi_expiry')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi_expiry')</label>
				<input class="form-control" placeholder="{{ __('basic.oi_expiry')}}" name="oi_expiry" type="date" value="{{ $employee->oi_expiry }}" required />
				{!! ($errors->has('oi_expiry') ? $errors->first('oi_expiry', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.mobile')</label>
				<input class="form-control" placeholder="{{ __('basic.mobile')}}" name="mobile" type="text" value="{{ $employee->mobile }}"  />
				{!! ($errors->has('mobile') ? $errors->first('mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_mobile')</label>
				<input class="form-control" placeholder="{{ __('basic.priv_mobile')}}" name="priv_mobile" type="text" value="{{ $employee->priv_mobile }}" />
				{!! ($errors->has('priv_mobile') ? $errors->first('priv_mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
				<label>e-mail</label>
				<input class="form-control" placeholder="e-mail" name="email" type="email" value="{{$employee->email }}"  />
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_email')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_email')</label>
				<input class="form-control" placeholder="Privatan e-mail" name="priv_email" type="email" value="{{ $employee->priv_email }}"  />
				{!! ($errors->has('priv_email') ? $errors->first('priv_email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_adresa')) ? 'has-error' : '' }}">
				<label>Adresa prebivališta</label>
				<input class="form-control" placeholder="" name="prebiv_adresa" type="text" value="{{ $employee->prebiv_adresa }}"  />
				{!! ($errors->has('prebiv_adresa') ? $errors->first('prebiv_adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_grad')) ? 'has-error' : '' }}">
				<label>Grad prebivališta</label>
				<input class="form-control" placeholder="" name="prebiv_grad" type="text" value="{{ $employee->prebiv_grad }}"  />
				{!! ($errors->has('prebiv_grad') ? $errors->first('prebiv_grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('borav_adresa')) ? 'has-error' : '' }}">
				<label>Adresa boravišta</label>
				<input class="form-control" placeholder="" name="borav_adresa" type="text" value="{{ $employee->borav_adresa }}"  />
				{!! ($errors->has('borav_adresa') ? $errors->first('borav_adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('borav_grad')) ? 'has-error' : '' }}">
				<label>Grad boravišta</label>
				<input class="form-control" placeholder="" name="borav_grad" type="text" value="{{ $employee->borav_grad }}"  />
				{!! ($errors->has('borav_grad') ? $errors->first('borav_grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label>@lang('basic.metier')</label>
				<input name="title" type="text" class="form-control" value="{{ $employee->title }}"  required >
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('qualifications')) ? 'has-error' : '' }}">
				<label>@lang('basic.qualifications')</label>
				<input name="qualifications" type="text" class="form-control" value="{{ $employee->qualifications }}"  required >
				{!! ($errors->has('qualifications') ? $errors->first('qualifications', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('marital'))  ? 'has-error' : '' }}">
				<label>@lang('basic.marital')</label>
				<select class="form-control" name="marital" value="{{ $employee->marital }}"  required >
					<option selected="selected" value=""></option>
					<option {!! $employee->marital == 'yes' ? 'selected' : '' !!}  value="yes" >@lang('basic.married')</option>
					<option {!! $employee->marital == 'no' ? 'selected' : '' !!}  value="no" >@lang('basic.not_married')</option>
				</select>
				{!! ($errors->has('bracno_stanje') ? $errors->first('bracno_stanje', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('work_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.work')</label>
				<select class="form-control" name="work_id" id="sel1" value="{{ $employee->work_id }}" required >
					<option selected="selected" value=""></option>
					@foreach($works as $work)
						<option name="work_id" value="{{ $work->id }}" {!! $employee->work_id == $work->id ? 'selected' : '' !!} >{{ $work->department['name'] . ' - '. $work->name }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('work_id') ? $errors->first('work_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('superior_id'))  ? 'has-error' : '' }}">
				<span><b>Nadređeni djelatnik:</b></span>
				<select class="form-control" name="superior_id" id="sel1" >
					<option selected value="0"></option>
					@foreach($employees as $djelatnik)
						<option name="superior_id" value="{{ $djelatnik->id }}" {!! $employee->superior_id == $djelatnik->id ? 'selected' : '' !!} >{{ $djelatnik->user['last_name'] . ' '. $djelatnik->user['first_name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('superior_id') ? $errors->first('superior_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('effective_cost'))  ? 'has-error' : '' }}">
				<span><b>Efektivna cijena sata:</b></span>
				<input class="form-control" name="effective_cost" type="text"  value="{{ $employee->effective_cost }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
				title="This must be a number with up to 2 decimal places" />
				{!! ($errors->has('effective_cost') ? $errors->first('effective_cost', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('brutto'))  ? 'has-error' : '' }}">
				<span><b>Brutto godišnja plaća:</b></span>
				<input class="form-control" name="brutto" type="text"  value="{{ $employee->brutto }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
				title="This must be a number with up to 2 decimal places" />
				{!! ($errors->has('brutto') ? $errors->first('brutto', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('reg_date')) ? 'has-error' : '' }}">
				<label>@lang('basic.reg_date')</label>
				<input class="form-control" placeholder="{{ __('basic.reg_date')}}" name="reg_date" type="date" value="{{ $employee->reg_date }}" required />
				{!! ($errors->has('reg_date') ? $errors->first('reg_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('probation')) ? 'has-error' : '' }}">
				<label>@lang('basic.probation')</label>
				<input class="form-control" placeholder="{{ __('basic.probation')}}" name="probation" type="text" value="{{ $employee->probation }}" pattern="[0-9]" />
				{!! ($errors->has('probation') ? $errors->first('probation', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('years_service'))  ? 'has-error' : '' }}">
				<label>Staž kod prošlog poslodavca (godina-mjeseci-dana):</label><br>
				<input name="stazY" type="text" class="staz" value="{{ $stazY }}" maxlength="2" required>-
				<input name="stazM" type="text" class="staz" value="{{ $stazM }}" maxlength="2" required>-
				<input name="stazD" type="text" class="staz" value="{{ $stazD }}" maxlength="2" required>
				{!! ($errors->has('years_service') ? $errors->first('years_service', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('termination_service'))  ? 'has-error' : '' }}">
				<input type="checkbox" name="termination_service" {!! $employee->termination_service  ? 'checked' : '' !!} > Prekid radnog odnosa više od 8 dana
					{!! ($errors->has('termination_service') ? $errors->first('termination_service', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<input type="checkbox" name="first_job" {!! $employee->first_job  ? 'checked' : '' !!} > Prvo zaposlenje
			</div>
			<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.comment') </label>
				<textarea class="form-control" name="comment">{{ $employee->comment }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.edit')}}">
		</fieldset>
	</form>
</div>
        