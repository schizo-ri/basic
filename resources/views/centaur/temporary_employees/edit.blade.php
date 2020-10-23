<span hidden class="locale" >{{ App::getLocale() }}</span>
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_employee')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('temporary_employees.update', $temporaryEmployee->id) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.user')</label>
				<select class="form-control" name="user_id" required>
					<option value="" disabled selected ></option>
					@foreach($users as $user)					
						@if( ! Sentinel::findById($user->id)->inRole('superadmin') && ! $employees->where('user_id', $user->id)->first() )
							<option value="{{ $user->id}}" {!! $temporaryEmployee->user_id ==  $user->id ? 'selected' : '' !!}>{{ $user->first_name . ' ' . $user->last_name }}</option>
						@endif					
					@endforeach
				</select>
				{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_day')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_day')</label>
				<input class="form-control" name="b_day" type="date" value="{{ $temporaryEmployee->b_day }}"  />
				{!! ($errors->has('b_day') ? $errors->first('b_day', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_place')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_place')</label>
				<input class="form-control"  name="b_place" type="text" value="{{ $temporaryEmployee->b_place }}" maxlength="50" />
				{!! ($errors->has('b_place') ? $errors->first('b_place', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('father_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.father_name')</label>
				<input class="form-control" name="father_name" type="text" value="{{ $temporaryEmployee->father_name }}" maxlength="20" />
				{!! ($errors->has('father_name') ? $errors->first('father_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mather_name')) ? 'has-error' : '' }}">
				<label>@lang('basic.mather_name')</label>
				<input class="form-control" name="mather_name" type="text" value="{{ $temporaryEmployee->mather_name }}" maxlength="20" />
				{!! ($errors->has('mather_name') ? $errors->first('mather_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
				<label>@lang('basic.oib')</label>
				<input class="form-control" name="oib" type="number" step="1" maxlength="20" value="{{ $temporaryEmployee->oib }}" required />
				{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi')</label>
				<input class="form-control" name="oi" type="number" step="1" maxlength="20" value="{{ $temporaryEmployee->oi }}"  />
				{!! ($errors->has('oi') ? $errors->first('oi', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oi_expiry')) ? 'has-error' : '' }}">
				<label>@lang('basic.oi_expiry')</label>
				<input class="form-control" name="oi_expiry" type="date" value="{{ $temporaryEmployee->oi_expiry }}"  />
				{!! ($errors->has('oi_expiry') ? $errors->first('oi_expiry', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.mobile')</label>
				<input class="form-control" name="mobile" type="text" maxlength="50" value="{{ $temporaryEmployee->mobile }}"  />
				{!! ($errors->has('mobile') ? $errors->first('mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_mobile')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_mobile')</label>
				<input class="form-control" name="priv_mobile" type="text" maxlength="50" value="{{ $temporaryEmployee->priv_mobile }}" />
				{!! ($errors->has('priv_mobile') ? $errors->first('priv_mobile', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
				<label>e-mail</label>
				<input class="form-control" name="email" type="email" maxlength="50" required value="{!! isset($user1) ? $user1->email : $temporaryEmployee->email  !!} " />
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('priv_email')) ? 'has-error' : '' }}">
				<label>@lang('basic.priv_email')</label>
				<input class="form-control" name="priv_email" type="email" maxlength="50" value="{{ $temporaryEmployee->priv_email }}"  />
				{!! ($errors->has('priv_email') ? $errors->first('priv_email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_adresa')) ? 'has-error' : '' }}">
				<label>Adresa prebivališta</label>
				<input class="form-control"name="prebiv_adresa" type="text" maxlength="50" value="{{ $temporaryEmployee->prebiv_adresa }}"  />
				{!! ($errors->has('prebiv_adresa') ? $errors->first('prebiv_adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('prebiv_grad')) ? 'has-error' : '' }}">
				<label>Grad prebivališta</label>
				<input class="form-control" name="prebiv_grad" type="text" maxlength="50" value="{{ $temporaryEmployee->prebiv_grad }}"  />
				{!! ($errors->has('prebiv_grad') ? $errors->first('prebiv_grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label>@lang('basic.metier')</label>
				<input name="title" type="text" class="form-control" maxlength="50" value="{{ $temporaryEmployee->title }}"   >
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('qualifications')) ? 'has-error' : '' }}">
				<label>@lang('basic.qualifications')</label>
				<input name="qualifications" type="text" class="form-control" maxlength="20" value="{{ $temporaryEmployee->qualifications }}"   >
				{!! ($errors->has('qualifications') ? $errors->first('qualifications', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('marital'))  ? 'has-error' : '' }}">
				<label>@lang('basic.marital')</label>
				<select class="form-control" name="marital" value="{{ $temporaryEmployee->marital }}"  >
					<option  value="yes" {!! $temporaryEmployee->marital == "yes"  ? 'selected' : '' !!}>@lang('basic.married')</option>
					<option  value="no" {!! $temporaryEmployee->marital == "no" ? 'selected' : '' !!}>@lang('basic.not_married')</option>
				</select>
				{!! ($errors->has('bracno_stanje') ? $errors->first('bracno_stanje', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('work_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.work')</label>
				<select class="form-control" name="work_id" value="{{ $temporaryEmployee->work_id }}" required >
					<option selected="selected" disabled></option>
					@foreach($works as $work)
						<option name="work_id" value="{{ $work->id }}" {!! $temporaryEmployee->work_id ==  $work->id ? 'selected' : '' !!} >{{ $work->name . ' - '. $work->department['name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('work_id') ? $errors->first('work_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('superior_id'))  ? 'has-error' : '' }}">
				<span><b>Nadređeni djelatnik:</b></span>
				<select class="form-control" name="superior_id" >
					<option selected value="0"></option>
					@foreach($employees as $employee)
						<option name="superior_id" value="{{ $employee->id }}" {!! $temporaryEmployee->superior_id ==  $employee->id ? 'selected' : '' !!} >{{ $employee->user['last_name'] . ' '. $employee->user['first_name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('superior_id') ? $errors->first('superior_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('reg_date')) ? 'has-error' : '' }}">
				<label>@lang('basic.reg_date')</label>
				<input class="form-control" placeholder="{{ __('basic.reg_date')}}" name="reg_date" type="date" value="{{ $temporaryEmployee->reg_date }}" required />
				{!! ($errors->has('reg_date') ? $errors->first('reg_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<input type="checkbox" name="checkout" value="1" > Odjava
			</div>
			<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.comment') </label>
				<textarea class="form-control" maxlength="65535" name="comment">{{ $temporaryEmployee->comment }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('size')) ? 'has-error' : '' }}">
				<label>@lang('basic.size')</label>
				<input name="size" type="text" class="form-control" maxlength="10" value="{{ $temporaryEmployee->size }}"   >
				{!! ($errors->has('size') ? $errors->first('size', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('shoe_size')) ? 'has-error' : '' }}">
				<label>@lang('basic.shoe_size')</label>
				<input name="shoe_size" type="text" class="form-control" maxlength="10" value="{{ $temporaryEmployee->shoe_size }}"   >
				{!! ($errors->has('shoe_size') ? $errors->first('shoe_size', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>