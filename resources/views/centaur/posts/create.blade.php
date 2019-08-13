@extends('Centaur::layout')

@section('title', __('basic.send_post'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.post')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('posts.store') }}" >
					<fieldset>
						<div class="form-group {{ ($errors->has('to_employee_id')) ? 'has-error' : '' }}">
							<label>@lang('basic.to')</label>
							<select class="form-control" name="to_employee_id" required>
									<option selected disabled</option>
									@foreach($employees as $employee)
									<option value="{{ $employee->id}}" >{{ $employee->first_name . ' ' .  $employee->last_name }}</option>
								@endforeach
							</select>
							{!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<!--<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}" >
							<label>@lang('basic.title')</label>
							<input class="form-control" placeholder="{{ __('basic.title')}}" name="title" type="text" value="{{ old('title') }}"  />
							{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
						</div>-->
						<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
							<label>@lang('basic.content')</label>
							<textarea name="content" type="text" class="form-control" rows="5" required >{{ old('content') }}</textarea>
							{!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
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