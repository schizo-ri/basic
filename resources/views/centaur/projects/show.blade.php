<div class="modal-header">
	<h3 class="panel-title">{{ $project->project_no . ' - ' .  $project->name  }}
		@if(Sentinel::getUser()->hasAccess(['projects.delete']))
			<form class="delete_form " action="{{ action('ProjectController@destroy', ['id' => $project->id]) }}" method="POST"  onSubmit="if(!confirm('Želiš li stvarno obrisati djelatnika na označeni dan?')){return false;}">
				@method('DELETE')
				@csrf
				<button type="submit" class="" title="Obriši projekt">
					<span class="btn glyphicon glyphicon-remove" aria-hidden="true"></span>
				</button>
			</form>
		@endif
	</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('projects.update', $project->id) }}">
		<fieldset>
			<div class="col-lg-6 col-md-12" >
				<div class="form-group {{ ($errors->has('project_no')) ? 'has-error' : '' }}">
					<label>Broj projekta</label>
					<input class="form-control"  name="project_no" type="text" value="{{ $project->project_no }}" required maxlength="20" />
					{!! ($errors->has('project_no') ? $errors->first('project_no', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
					<label>Naziv</label>
					<input class="form-control"  name="name" type="text" value="{{ $project->name }}" required maxlength="191" />
					{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('start_date')) ? 'has-error' : '' }}">
					<label>Planirani početak radova</label>
					<input class="form-control"  name="start_date" type="date" value="{{ $project->start_date }}" required />
					{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('end_date')) ? 'has-error' : '' }}">
					<label>Datum isporuke</label>
					<input class="form-control"  name="end_date" type="date" value="{{ $project->end_date }}"  />
					{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('duration')) ? 'has-error' : '' }}">
					<label>Procjenjeno trajanje [h]</label>
					<input class="form-control"  name="duration" type="text" pattern="\d*" value="{{ $project->duration }}" required title="Dozvoljen unos samo cijelog broja" />
					{!! ($errors->has('duration') ? $errors->first('duration', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('day_hours')) ? 'has-error' : '' }}">
					<label>Dnevno sati rada [h]</label>
					<input class="form-control"  name="day_hours" type="text" pattern="\d*" value="{{ $project->day_hours }}" required title="Dozvoljen unos samo cijelog broja" />
					{!! ($errors->has('day_hours') ? $errors->first('day_hours', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('saturday')) ? 'has-error' : '' }}">
					<label>Rad subotom</label>
					<input class="" name="saturday" type="radio" value="0" {!! $project->saturday == 0 ? 'checked' : '' !!}  /> NE
					<input class="" name="saturday" type="radio" value="1" {!! $project->saturday == 1 ? 'checked' : '' !!}  /> DA
					{!! ($errors->has('saturday') ? $errors->first('saturday', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('categories')) ? 'has-error' : '' }}">
					@foreach ($categories as $category)
						<p class="proj_cat">
							<input type="checkbox" id="cat_{{ $category->id }}" name="categories[]" value="{{ $category->id }}" /> 
							<label for="cat_{{ $category->id }}">{{ $category->mark . ' | ' .  $category->description }}</label>
						</p>
					@endforeach
				</div>
			</div>
			<div class="col-lg-6 col-md-12" >
				@if(Sentinel::getUser()->hasAccess(['project_employees.create']))
					<div class="modal-footer projectEmployees">
						<h5>Djelatnici: 
							<label class="filter_employee">
								<input type="search" placeholder="Traži" id="mySearch" name="search">
								<i class="clearable__clear">&times;</i>
							</label>
						</h5>
						@php
							$count = count( $employees );
							$half_count1 = intval($count/2);
							$half_count2 = $half_count1;
							if($count % 2) {
								$half_count1++;
							}
						@endphp
						<div class="col-6">
							@foreach ($employees->slice(0,$half_count1) as $employee)
								<p class="employee" title="{{  $employee->category['description'] }}"><input name="employee_id[]" type="checkbox" id="id{{ $employee->id }}" value="{{ $employee->id }}" {{ $projectEmployees->where('employee_id', $employee->id)->first() ? 'checked' : '' }} /><label for="id{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name . ' [' . $employee->category['mark'] . ']' }}</label></p>
							@endforeach
						</div>
						<div class="col-6">
							@foreach ($employees->slice($half_count1,$half_count2) as $employee)		
								<p class="employee" title="{{  $employee->category['description'] }}" ><input name="employee_id[]" type="checkbox" id="id{{ $employee->id }}" value="{{ $employee->id }}" {{ $projectEmployees->where('employee_id', $employee->id)->first() ? 'checked' : '' }} /><label for="id{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name . ' [' . $employee->category['mark'] . ']' }}</label></p>
							@endforeach
						</div>
					</div>
					<input type="text" name="project_id" value="{{ $project->id }}" hidden>
				@endif
			</div>
		
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="Spremi" >
		</fieldset>
	</form>
</div>
<script>	
	$.getScript('/../js/filter.js');
</script>


