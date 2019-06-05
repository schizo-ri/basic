@extends('Centaur::layout')

@section('title', __('basic.add_education'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.add_education')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('education.store') }}">
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
						<label>@lang('basic.name')</label>
						<input name="name" type="text" class="form-control" value="{{ old('name') }}">
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
						<label>@lang('basic.to_department'):</label>
						<select class="form-control multiple_box" name="to_department_id[]" value="{{ old('to_department_id') }}" multiple size="10">
							@foreach($departments0 as $department0)
								<option value="{{ $department0->id }}" class="bold">{{ $department0->name }}</option>
								@foreach($departments1->where('level2',$department0->id) as $department1)
									<option value="{{ $department1->id }}" class="padd_l_10 bold">&#11169; {{ $department1->name }}</option>
									@foreach($departments2->where('level2',$department1->id) as $department2)
										<option value="{{ $department2->id }}" class="padd_l_30">&#11169; {{ $department2->name }}</option>
									@endforeach
								@endforeach
							@endforeach
						</select>
					</div>
					<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
						<label>Status</label>
						<input type="radio" class="" name="status" value="neaktivna" checked />@lang('basic.inactive')  
						<input type="radio" class="" name="status" value="aktivna" />@lang('basic.active') 
						{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
				</form>
            </div>
        </div>
    </div>
</div>
@stop