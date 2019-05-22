@extends('Centaur::layout')

@section('title', __('clients.add_client'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('clients.create_client')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('clients.store') }}">
					<fieldset>
						<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ old('name') }}" />
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.address')}}" name="address" type="text" value="{{ old('address') }}" />
							{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.city')}}" name="city" type="text" value="{{ old('city') }}" />
							{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.oib')}}" name="oib" type="text" value="{{ old('oib') }}" />
							{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						 <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.f_name')}}" name="first_name" type="text" value="{{ old('first_name') }}" />
							{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.l_name')}}" name="last_name" type="text" value="{{ old('last_name') }}" />
							{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}">
							{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('clients.phone')}}" name="phone" type="text" value="{{ old('phone') }}">
							{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<input name="_token" value="{{ csrf_token() }}" type="hidden">
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.save')}}">
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop