<div class="modal-header">
	<h3 class="panel-title">{{ $project->project_no . ' - ' .  $project->name  }}
		<a href="{{ route('projects.edit', $project->id) }}" class="btn" rel="modal:open">
			<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
		</a>
		<form class="delete_form " action="{{ action('ProjectController@destroy', ['id' => $project->id]) }}" method="POST"  onSubmit="if(!confirm('Želiš li stvarno obrisati djelatnika na označeni dan?')){return false;}">
			@method('DELETE')
			@csrf
			<button type="submit" class="" title="Obriši projekt">
				<span class="btn glyphicon glyphicon-remove" aria-hidden="true"></span>
			</button>
		</form>
	</h3>
</div>
<div class="modal-body">
	<p>Broj projekta: {{ $project->project_no }}</p>
	<p>Naziv projekta: {{ $project->name }}</p>
	<p>Planirani početak rada: {{ $project->start_date }}</p>
	<p>Planirani datum isporuke: {{ $project->end_date }}</p>
	<p>Procjenjeno trajanje: {{ $project->duration }} h</p>
	<p>Planirani sati rada u danu: {{ $project->day_hours }} h</p>
	<p>Rad subotom: {!! $project->saturday == 1 ? 'Da' : 'Ne' !!}</p>
</div>
<div class="modal-footer projectEmployees">
	<h5>Djelatnici: 
		<label class="filter_employee">
			<input type="search" placeholder="Traži" id="mySearch" name="search">
			<i class="clearable__clear">&times;</i>
		</label>
	</h5>
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('project_employees.store') }}">
		@php
			$count = count( $employees);
			$half_count1 = intval($count/2);
			$half_count2 = $half_count1;
			if($count % 2) {
				$half_count1++;
			}
		@endphp
			<div class="col-6">
				@foreach ($employees->slice(0,$half_count1) as $employee)		
					<p class="employee" title="{{  $employee->category['description'] }}"><input name="employee_id[]" type="checkbox" value="{{ $employee->id }}" {{ $projectEmployees->where('employee_id', $employee->id)->first() ? 'checked' : '' }} />{{ $employee->first_name . ' ' . $employee->last_name . ' [' . $employee->category['mark'] . ']' }}</p>
				@endforeach
			</div>
			<div class="col-6">
				@foreach ($employees->slice($half_count1,$half_count2) as $employee)		
					<p class="employee" title="{{  $employee->category['description'] }}" ><input name="employee_id[]" type="checkbox" value="{{ $employee->id }}" {{ $projectEmployees->where('employee_id', $employee->id)->first() ? 'checked' : '' }} />{{ $employee->first_name . ' ' . $employee->last_name  . ' [' . $employee->category['mark'] . ']'  }}</p>
				@endforeach
			</div>
		<input name="project_id" value="{{ $project->id }}" hidden/>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="Spremi">
	</form>
</div>
<script>	
	$.getScript('/../js/filter.js');
</script>


