@extends('Centaur::layout')

@section('title', __('basic.edit_post'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_post')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('posts.update',$post->id) }}">
					<fieldset>
						<div class="form-group {{ ($errors->has('to_employee_id')) ? 'has-error' : '' }}">
							<label>@lang('basic.to')</label>
							<select class="form-control" name="to_employee_id" required>
								@foreach($employees as $employee)
									<option value="{{ $employee->id}}" {!! $post->to_employee_id == $employee->id ? 'selected' : '' !!}>{{ $employee->first_name . ' ' .  $employee->last_name }}</option>
								@endforeach
							</select>
							{!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<!--<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}" >
							<label>@lang('basic.title')</label>
							<input class="form-control" placeholder="{{ __('basic.title')}}" name="title" type="text" value="{{ $post->title }}"  />
							{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
						</div>-->
						<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
							<label>@lang('basic.content')</label>
							<textarea name="content" type="text" class="form-control" rows="5" >{{ $post->content }}</textarea>
							{!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						{{ csrf_field() }}
						{{ method_field('PUT') }}
						<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop