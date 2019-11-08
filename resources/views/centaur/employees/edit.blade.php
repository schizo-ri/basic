<div class="modal-header">
	<h3 class="panel-title">Ispravi djelatnika</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employees.update', $employee->id) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
				<label>Ime</label>
				<input class="form-control"  name="first_name" type="text" value="{{ $employee->first_name }}" required />
				{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
				<label>Prezime</label>
				<input class="form-control"  name="last_name" type="text" value="{{ $employee->last_name }}" required />
				{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi">
		</fieldset>
	</form>
</div>