@extends('Centaur::layout')

@section('title', __('basic.add_work'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.add_work')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('works.store') }}" enctype="multipart/form-data">
					<fieldset>
						<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" >
							<label>@lang('basic.department')</label>
							<select class="form-control" name="department_id" required value="{{ old('department_id') }}" required >
								<option value="" disabled selected ></option>
								@foreach($departments as $department)
									<option value="{{ $department->id}}" {!! isset($department1) && $department1->id ==  $department->id ? 'selected' : '' !!} >{{ $department->name }}</option>
								@endforeach
							</select>
							{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
							<label>@lang('basic.name')</label>
							<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ old('name') }}" required />
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('job_description')) ? 'has-error' : '' }}">
							<label>@lang('basic.job_description')</label>
							<textarea name="job_description" type="text" class="form-control" rows="5" >{{ old('job_description') }}</textarea>
							{!! ($errors->has('job_description') ? $errors->first('job_description', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
							<label>@lang('basic.director')</label>
							<select class="form-control" name="employee_id">
								<option value="" disabled selected ></option>
								@foreach($employees as $employee)
									<option value="{{ $employee->id}}" >{{ $employee->first_name . ' ' .  $employee->last_name }}</option>
								@endforeach
							</select>
							{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
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