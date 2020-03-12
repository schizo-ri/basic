@extends('Centaur::layout')

@section('title', __('welcome.register'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('welcome.register')</h3>
            </div>
            <div class="panel-body">
			   <form accept-charset="UTF-8" role="form" method="post" action="{{ route('client_requests.store') }}">
					<fieldset>
						<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ old('name') }}" required />
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.address')}}" name="address" type="text" value="{{ old('address') }}" required />
							{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.city')}}" name="city" type="text" value="{{ old('city') }}" required />
							{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.oib')}}" name="oib" type="text" value="{{ old('oib') }}" required />
							{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						 <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.f_name')}}" name="first_name" type="text" value="{{ old('first_name') }}"  />
							{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.l_name')}}" name="last_name" type="text" value="{{ old('last_name') }}" />
							{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}" required >
							{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.phone')}}" name="phone" type="text" value="{{ old('phone') }}" required>
							{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<label>Moduli</label>
						<div class="checkbox">
							@foreach($modules as $module)
								<label>
									<input type="checkbox" name="module[]" value="{{ $module->id}}"/>{{ $module->name}} ({{ $module->description}})
								</label><br>
							@endforeach
						</div>
						<div class="form-group input_db {{ ($errors->has('db')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.create_db')}}" name="db" type="text" value="{{ old('db') }}" >
							{!! ($errors->has('db') ? $errors->first('db', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group input_url {{ ($errors->has('url')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="URL [icom-user.duplico.hr]" name="url" type="text" value="{{ old('url') }}" >
							{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group  {{ ($errors->has('price_per_user')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="Cijena po korisniku" name="price_per_user" type="number" step="0.01" value="{{ old('price_per_user') }}" >
							{!! ($errors->has('price_per_user') ? $errors->first('price_per_user', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group  {{ ($errors->has('no_users')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="Broj korisnika" name="no_users" type="number" value="{{ old('no_users') }}" >
							{!! ($errors->has('no_users') ? $errors->first('no_users', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group  {{ ($errors->has('calculate_method')) ? 'has-error' : '' }}">
							<label for="calculate_method">Način obračuna</label>
							<select class="form-control" name="calculate_method" value="{{ old('calculate_method') }}" id="calculate_method" >
								<option value="month">mjesečno</option>
								<option value="year">godišnje</option>
							</select>
							{!! ($errors->has('calculate_method') ? $errors->first('calculate_method', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<input name="_token" value="{{ csrf_token() }}" type="hidden">
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('welcome.signUp')}}">
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
	$('.input_db').change(function(){
		if( $(this).val() != '') {
			$('.input_url').attr('required','true');
		} else {
			$('.input_url').removeAttr('required');
		}
	});
	$('.input_url').change(function(){
		if( $(this).val() != '') {
			$('.input_db').attr('required','true');
		} else {
			$('.input_db').removeAttr('required');
		}
	});
</script>
@stop