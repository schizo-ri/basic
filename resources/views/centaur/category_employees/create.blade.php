<div class="modal-header">
	<h3 class="panel-title">Nova kategorija</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('category_employees.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('mark')) ? 'has-error' : '' }}">
				<label>Oznaka</label>
				<input class="form-control"  name="mark" type="text" value="{{ old('mark') }}" maxlength="2" required autofocus/>
				{!! ($errors->has('mark') ? $errors->first('mark', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label>Opis</label>
				<input class="form-control"  name="description" type="text" value="{{ old('description') }}" maxlength="255" required />
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Spremi">
		</fieldset>
	</form>
</div>