<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_education')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('educations.update', $education->id) }}">
		<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input name="name" type="text" class="form-control" value="{{ $education->name }}">
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
			<label>@lang('basic.to_department'):</label>
			<select class="form-control multiple_box" name="to_department_id" value="{{ old('to_department_id') }}" size="10">
				@foreach($departments0 as $department0)
					<option value="{{ $department0->id }}" class="bold"  {!!  in_array($department0->id, (explode(",",$education->to_department_id))) ? 'selected' : '' !!} >{{ $department0->name }}</option>
					@foreach($departments1->where('level2',$department0->id) as $department1)
						<option value="{{ $department1->id }}" class="padd_l_10 bold" {!!  in_array($department1->id, (explode(",",$education->to_department_id))) ? 'selected' : '' !!} >&#11169; {{ $department1->name }}</option>
						@foreach($departments2->where('level2',$department1->id) as $department2)
							<option value="{{ $department2->id }}" class="padd_l_30" {!!  in_array($department2->id, (explode(",",$education->to_department_id))) ? 'selected' : '' !!} >&#11169; {{ $department2->name }}</option>
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
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}" id="stil1">
	</form>
</div>