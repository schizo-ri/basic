<div class="modal-header">
	<h3 class="panel-title">Novi djelatnik</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employees.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
				<label>Ime</label>
				<input class="form-control" name="first_name" type="text" value="{{ old('first_name') }}" maxlength="50"  required autofocus/>
				{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
				<label>Prezime</label>
				<input class="form-control" name="last_name" type="text" value="{{ old('last_name') }}" maxlength="50" required />
				{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('category_id')) ? 'has-error' : '' }}">
					<label>Kategorija</label>
					<select name="category_id" class="form-control"  value="{{ old('category_id') }}" required>
						<label>Ime</label><option selected disabled></option>
						@foreach ($categories as $category)
							<option value="{{  $category->id }}">{{  $category->mark . ' | ' . $category->description }}</option>
						@endforeach
					</select>
					{!! ($errors->has('category_id') ? $errors->first('category_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Spremi">
		</fieldset>
	</form>
</div>