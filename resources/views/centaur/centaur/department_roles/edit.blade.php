
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_permissions')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('department_roles.update', $departmentRole->id ) }}">
		<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" >
			<label>{{ $departmentRole->department['name'] }}</label>
			<input hidden  name="department_id" value="{{ $departmentRole->department_id }}">
			{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<h5>@lang('basic.permissions'): <span class="modal_filter"><input type="search" placeholder="{{ __('basic.search')}}" id="mySearch"></span></h5>
		@foreach($tables as $table_name => $table_description)
			@foreach($methodes as  $methode_name => $methode_description)
				<div class="checkbox col-6 float_l panel">
					<label>
						<input type="checkbox" name="permissions[{{$table_name}}.{{$methode_name}}]" value="1" {!! in_array($table_name . '.' . $methode_name , $permissions)  ? 'checked' : '' !!}  >
						{{$table_description}}  - {{$methode_description}}
					</label>
				</div>
			@endforeach
		@endforeach
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
$.getScript( '/../js/filter.js');
</script>