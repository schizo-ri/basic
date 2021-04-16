<div class="modal-header">
	<h3 class="panel-title">Ispravi dodjelu</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('designing_employees.update', $designingEmployee->id) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('designing_id')) ? 'has-error' : '' }}">
				<label>Projekt</label>
				<select name="designing_id" class="form-control" value="{{ old('designing_id') }}" >
					<option selected disabled></option>
					@foreach ($designings as $designing)
						<option value="{{ $designing->id }}" {!! $designingEmployee->designing_id == $designing->id ? 'selected' : '' !!}>{{  $designing->project_no . ' ' . $designing->name . ' - ' . $designing->cabinet_name  }}</option>
					@endforeach
				</select>
				{!! ($errors->has('designing_id') ? $errors->first('designing_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}">
				<label>Projektant</label>
				<select name="user_id" class="form-control"  value="{{ old('user_id') }}" >
					<option selected disabled></option>
					@foreach ($employees_designins as $projektant)
						<option value="{{ $projektant->id }}"  {!! $designingEmployee->user_id == $projektant->id ? 'selected' : '' !!}>{{  $projektant->first_name . ' ' . $projektant->last_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('start_date')) ? 'has-error' : '' }}">
				<label>Datum</label>
				<input class="form-control" name="start_date" type="date" value="{{ $designingEmployee->start_date }}" required />
				{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			@csrf
			@method('PUT')
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Spremi">
		</fieldset>
	</form>
</div>