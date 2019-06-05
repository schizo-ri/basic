@extends('Centaur::layout')

@section('title', __('basic.edit_department'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_department')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('departments.update', $department->id) }}" enctype="multipart/form-data">
					<fieldset>
						<div class="form-group {{ ($errors->has('company_id')) ? 'has-error' : '' }}">
							<select class="form-control" name="company_id" required>
								@foreach($companies as $company)
									<option value="{{ $company->id}}" {!! $company->id == $department->company_id ? 'selected' : ''!!} >{{ $company->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ $department->name }}" required />
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
							<label>E-mail</label>
							<input name="email" type="email" class="form-control" value="{{ $department->email }}" required>
							{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group" id="razina" >
							<label>@lang('basic.level')</label>
							<select class="form-control" name="level1" id="level" required>
								<option value="0" {!! $department->level1 == '0' ? 'selected' : '' !!} >0. @lang('basic.level')</option>
								<option value="1" {!! $department->level1 == '1' ? 'selected' : '' !!} >1. @lang('basic.level')</option>
								<option value="2" {!! $department->level1 == '2' ? 'selected' : '' !!} >2. @lang('basic.level')</option>
							</select>
						</div>
						@if($departments)
						<div class="form-group" id="level1" hidden>
							<label>1. @lang('basic.level')</label>
							<select class="form-control" name="level2" value="" id="select_level">
								<option value="" selected ></option>
								@foreach($departments as $odjel)
									<option class="level {{ $odjel->level1 }}" value="{{ $odjel->id }}" {!! $odjel->id == $department->level2 ? 'selected' : '' !!} >{{$odjel->name }}</option>
								@endforeach
							</select>
						</div>
						@endif
						{{ csrf_field() }}
						{{ method_field('PUT') }}
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.edit')}}">
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    var val_level = $("#level").val();
	console.log(val_level);
	if(val_level == 1){
		$('#level1').show();
		$('.level.1').hide();
	}
	$('#level').change(function(){
		var level = $(this).val();
		if(level == 1 || level == 2){
			$('#level1').show();
			$('#select_level').prop('required',true);
			if(level == 1) {
				$('.level.1').hide();
			}else {
				$('.level.1').show();
			}
		}else{
			$('#level1').hide();
			$('#select_level').prop('required',false);
		}
	});
});
</script>
@stop