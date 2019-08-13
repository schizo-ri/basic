@extends('Centaur::layout')

@section('title', __('basic.add_employee'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">

		<div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.add_employee')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('employees.store') }}">
					<fieldset>
						<div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}">
							<label>@lang('basic.user')</label>
							<select class="form-control" name="user_id" required>
								<option value="" disabled selected ></option>
								@foreach($users as $user)
									@if(! $employees->where('user_id', $user->id)->first())
										<option value="{{ $user->id}}" {!! isset($user1) && $user1->id ==  $user->id ? 'selected' : '' !!}>{{ $user->first_name . ' ' . $user->last_name }}</option>
									@endif
								@endforeach
							</select>
							{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('b_day')) ? 'has-error' : '' }}">
							<label>@lang('basic.b_day')</label>
							<input class="form-control" placeholder="{{ __('basic.b_day')}}" name="b_day" type="date" value="{{ old('b_day') }}" required />
							{!! ($errors->has('b_day') ? $errors->first('b_day', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('b_place')) ? 'has-error' : '' }}">
							<label>@lang('basic.b_place')</label>
							<input class="form-control" placeholder="{{ __('basic.b_place')}}" name="b_place" type="text" value="{{ old('b_place') }}" required />
							{!! ($errors->has('b_place') ? $errors->first('b_place', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('father_name')) ? 'has-error' : '' }}">
							<label>@lang('basic.father_name')</label>
							<input class="form-control" placeholder="{{ __('basic.father_name')}}" name="father_name" type="text" value="{{ old('father_name') }}"  />
							{!! ($errors->has('father_name') ? $errors->first('father_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('mather_name')) ? 'has-error' : '' }}">
							<label>@lang('basic.mather_name')</label>
							<input class="form-control" placeholder="{{ __('basic.mather_name')}}" name="mather_name" type="text" value="{{ old('mather_name') }}"  />
							{!! ($errors->has('mather_name') ? $errors->first('mather_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
							<label>@lang('basic.oib')</label>
							<input class="form-control" placeholder="{{ __('basic.oib')}}" name="oib" type="text" value="{{ old('oib') }}" required />
							{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('oi')) ? 'has-error' : '' }}">
							<label>@lang('basic.oi')</label>
							<input class="form-control" placeholder="{{ __('basic.oi')}}" name="oi" type="text" value="{{ old('oi') }}" required />
							{!! ($errors->has('oi') ? $errors->first('oi', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('oi_expiry')) ? 'has-error' : '' }}">
							<label>@lang('basic.oi_expiry')</label>
							<input class="form-control" placeholder="{{ __('basic.oi_expiry')}}" name="oi_expiry" type="date" value="{{ old('oi_expiry') }}" required />
							{!! ($errors->has('oi_expiry') ? $errors->first('oi_expiry', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('mobile')) ? 'has-error' : '' }}">
							<label>@lang('basic.mobile')</label>
							<input class="form-control" placeholder="{{ __('basic.mobile')}}" name="mobile" type="text" value="{{ old('mobile') }}"  />
							{!! ($errors->has('mobile') ? $errors->first('mobile', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('priv_mobile')) ? 'has-error' : '' }}">
							<label>@lang('basic.priv_mobile')</label>
							<input class="form-control" placeholder="{{ __('basic.priv_mobile')}}" name="priv_mobile" type="text" value="{{ old('priv_mobile') }}" />
							{!! ($errors->has('priv_mobile') ? $errors->first('priv_mobile', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
							<label>e-mail</label>
							<input class="form-control" placeholder="e-mail" name="email" type="email" value="{!! isset($user1) ? $user1->email : old('email') !!}" />
							{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('priv_email')) ? 'has-error' : '' }}">
							<label>@lang('basic.priv_email')</label>
							<input class="form-control" placeholder="Privatan e-mail" name="priv_email" type="email" value="{{ old('priv_email') }}"  />
							{!! ($errors->has('priv_email') ? $errors->first('priv_email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('prebiv_adresa')) ? 'has-error' : '' }}">
							<label>Adresa prebivališta</label>
							<input class="form-control" placeholder="" name="prebiv_adresa" type="text" value="{{ old('prebiv_adresa') }}"  />
							{!! ($errors->has('prebiv_adresa') ? $errors->first('prebiv_adresa', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('prebiv_grad')) ? 'has-error' : '' }}">
							<label>Grad prebivališta</label>
							<input class="form-control" placeholder="" name="prebiv_grad" type="text" value="{{ old('prebiv_grad') }}"  />
							{!! ($errors->has('prebiv_grad') ? $errors->first('prebiv_grad', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('borav_adresa')) ? 'has-error' : '' }}">
							<label>Adresa boravišta</label>
							<input class="form-control" placeholder="" name="borav_adresa" type="text" value="{{ old('borav_adresa') }}"  />
							{!! ($errors->has('borav_adresa') ? $errors->first('borav_adresa', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('borav_grad')) ? 'has-error' : '' }}">
							<label>Grad boravišta</label>
							<input class="form-control" placeholder="" name="borav_grad" type="text" value="{{ old('borav_grad') }}"  />
							{!! ($errors->has('borav_grad') ? $errors->first('borav_grad', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
							<label>@lang('basic.metier')</label>
							<input name="title" type="text" class="form-control" value="{{ old('title') }}"  required >
							{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('qualifications')) ? 'has-error' : '' }}">
							<label>@lang('basic.qualifications')</label>
							<input name="qualifications" type="text" class="form-control" value="{{ old('qualifications') }}"  required >
							{!! ($errors->has('qualifications') ? $errors->first('qualifications', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group  {{ ($errors->has('marital'))  ? 'has-error' : '' }}">
							<label>@lang('basic.marital')</label>
							<select class="form-control" name="marital" value="{{ old('marital') }}"  required >
								<option selected="selected" value=""></option>
								<option  value="yes">@lang('basic.married')</option>
								<option  value="no">@lang('basic.not_married')</option>
							</select>
							{!! ($errors->has('bracno_stanje') ? $errors->first('bracno_stanje', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('work_id'))  ? 'has-error' : '' }}">
							<label>@lang('basic.work')</label>
							<select class="form-control" name="work_id" id="sel1" value="{{ old('work_id') }}" required >
								<option selected="selected" value=""></option>
								@foreach($works as $work)
									<option name="work_id" value="{{ $work->id }}">{{ $work->department['name'] . ' - '. $work->name }}</option>
								@endforeach	
							</select>
							{!! ($errors->has('work_id') ? $errors->first('work_id', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('superior_id'))  ? 'has-error' : '' }}">
							<span><b>Nadređeni djelatnik:</b></span>
							<select class="form-control" name="superior_id" id="sel1" >
								<option selected value="0"></option>
								@foreach($employees as $employee)
									<option name="superior_id" value="{{ $employee->id }}">{{ $employee->user['last_name'] . ' '. $employee->user['first_name'] }}</option>
								@endforeach	
							</select>
							{!! ($errors->has('superior_id') ? $errors->first('superior_id', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('effective_cost'))  ? 'has-error' : '' }}">
							<span><b>Efektivna cijena sata:</b></span>
							<input class="form-control" name="effective_cost" type="text"  value="{{ old('effective_cost') }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
							title="This must be a number with up to 2 decimal places" />
							{!! ($errors->has('effective_cost') ? $errors->first('effective_cost', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('brutto'))  ? 'has-error' : '' }}">
							<span><b>Brutto godišnja plaća:</b></span>
							<input class="form-control" name="brutto" type="text"  value="{{ old('brutto') }}" pattern="[-+]?[0-9]*[.,]?[0-9]+"
							title="This must be a number with up to 2 decimal places" />
							{!! ($errors->has('brutto') ? $errors->first('brutto', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('reg_date')) ? 'has-error' : '' }}">
							<label>@lang('basic.reg_date')</label>
							<input class="form-control" placeholder="{{ __('basic.reg_date')}}" name="reg_date" type="date" value="{{ old('reg_date') }}" required />
							{!! ($errors->has('reg_date') ? $errors->first('reg_date', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('probation')) ? 'has-error' : '' }}">
							<label>@lang('basic.probation')</label>
							<input class="form-control" placeholder="{{ __('basic.probation')}}" name="probation" type="text" value="{{ old('probation') }}" pattern="[0-9]" />
							{!! ($errors->has('probation') ? $errors->first('probation', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('years_service'))  ? 'has-error' : '' }}">
							<label>Staž kod prošlog poslodavca (godina-mjeseci-dana):</label><br>
							<input name="stazY" type="text" class="staz" value="0" maxlength="2" required>-
							<input name="stazM" type="text" class="staz" value="0" maxlength="2" required>-
							<input name="stazD" type="text" class="staz" value="0" maxlength="2" required>
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
							<textarea class="form-control" name="comment"></textarea>
							{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						{{ csrf_field() }}
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.save')}}">
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop