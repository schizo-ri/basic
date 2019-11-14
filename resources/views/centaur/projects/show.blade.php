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
	<p>Procjenjeno trajanje: {{ $project->duration }} h</p>
	<p>Planirani sati rada u danu: {{ $project->day_hours }} h</p>
	<p>Rad subotom: {!! $project->saturday == 1 ? 'Da' : 'Ne' !!}</p>
</div>
<div class="modal-footer projectEmployees">
	<h5>Djelatnici na projektu: </h5>
	@foreach ($projectEmployees as $projectEmployee)
		<p>{{$projectEmployee->employee['first_name'] . ' ' . $projectEmployee->employee['last_name'] }}</p>
	@endforeach
</div>

