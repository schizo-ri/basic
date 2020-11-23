<div class="modal-header">
	<h3 class="panel-title">Unesi novo projektiranje</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('designings.store') }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('project_no')) ? 'has-error' : '' }}">
				<label>Broj projekta</label>
				<input class="form-control" name="project_no" type="text" value="{{ old('project_no') }}" maxlength="50" required autofocus/>
				{!! ($errors->has('project_no') ? $errors->first('project_no', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label>Naziv projekta</label>
				<input class="form-control" name="name" type="text" value="{{ old('name') }}" maxlength="191" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
				<label>Datum isporuke</label>
				<input class="form-control" name="date" type="date" value="{{ old('date') }}" required />
				{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('manager_id')) ? 'has-error' : '' }}">
				<label>Voditelj projekta</label>
				<select name="manager_id" class="form-control"  value="{{ old('manager_id') }}" required>
					<option selected disabled></option>
					@foreach ($voditelji as $voditelj)
						<option value="{{ $voditelj->id }}">{{  $voditelj->first_name . ' ' . $voditelj->last_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('manager_id') ? $errors->first('manager_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			@if (Sentinel::inRole('administrator'))
				<div class="form-group {{ ($errors->has('designer_id')) ? 'has-error' : '' }}">
					<label>Projektant</label>
					<select name="designer_id" class="form-control"  value="{{ old('designer_id') }}" >
						<option selected disabled></option>
						@foreach ($projektanti as $projektant)
							<option value="{{ $projektant->id }}">{{  $projektant->first_name . ' ' . $projektant->last_name }}</option>
						@endforeach
					</select>
					{!! ($errors->has('designer_id') ? $errors->first('designer_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@endif
			<div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
				<label>Napomena</label>
				<textarea class="form-control" name="comment" type="text"  rows="3" maxlength="5592415">{{ old('comment') }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		{{-- 	<div class="form-group {{ ($errors->has('fileToUpload')) ? 'has-error' : '' }}">
				<label>Dodaj dokumenat</label>
				<input type="file" name="fileToUpload" id="fileToUpload">
				{!! ($errors->has('fileToUpload') ? $errors->first('fileToUpload', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Naziv dokumenta</label><input type="text" class="form-control" name="file_name" id="file_name" value="">
			</div> --}}
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Spremi">
		</fieldset>
	</form>
</div>
<script>
	$('#fileToUpload').change(function(e){
	   var filename = e.target.files[0].name;
	   console.log(filename.substr(0, filename.lastIndexOf('.')));
	   $('#file_name').val(filename.substr(0, filename.lastIndexOf('.')));
   });
</script>