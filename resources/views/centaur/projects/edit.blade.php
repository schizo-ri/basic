<div class="modal-header">
	<h3 class="panel-title">Ispravi projekt</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('projects.update', $project->id) }}">
		<fieldset>
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
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="Spremi">
		</fieldset>
	</form>
</div>