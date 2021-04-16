<div class="modal-header">
	<h3 class="panel-title">Ispravi projekt</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('designings.update', $designing->id ) }}"  enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('project_no')) ? 'has-error' : '' }}">
				<label>Broj projekta</label>
				<input class="form-control" name="project_no" type="text" value="{{ $designing->project_no }}" maxlength="50" required autofocus/>
				{!! ($errors->has('project_no') ? $errors->first('project_no', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label>Naziv projekta</label>
				<input class="form-control" name="name" type="text" value="{{ $designing->name }}" maxlength="191" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('cabinet_name')) ? 'has-error' : '' }}">
				<label>Naziv ormara</label>
				<input class="form-control" name="cabinet_name" type="text" value="{{ $designing->cabinet_name }}" maxlength="50" required />
				{!! ($errors->has('cabinet_name') ? $errors->first('cabinet_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
				<label>Datum završetka pripreme</label>
				<input class="form-control" name="date" type="date" value="{{  $designing->date }}" required />
				{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('manager_id')) ? 'has-error' : '' }}">
				<label>Voditelj projekta</label>
				<select name="manager_id" class="form-control" required>
					<option selected disabled></option>
					@foreach ($voditelji as $user)
						<option value="{{ $user->id }}"  {!! $designing->manager_id == $user->id ? 'selected' : '' !!}>{{ $user->first_name . ' ' . $user->last_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('manager_id') ? $errors->first('manager_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			@if (Sentinel::inRole('administrator'))
				<div class="form-group {{ ($errors->has('designer_id')) ? 'has-error' : '' }}">
					<label>Projektant</label>
					<select name="designer_id" class="form-control" >
						<option selected disabled></option>
						@foreach ($projektanti as $user)
							<option value="{{ $user->id }}" {!! $designing->designer_id == $user->id ? 'selected' : '' !!} >{{ $user->first_name . ' ' . $user->last_name }}</option>
						@endforeach
					</select>
					{!! ($errors->has('designer_id') ? $errors->first('designer_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@endif
			<div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
				<label>Napomera</label>
				<textarea class="form-control" name="comment" type="text" rows="3" maxlength="5592415">{{ $designing->comment }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{-- <div class="form-group {{ ($errors->has('fileToUpload')) ? 'has-error' : '' }}">
				<label>Dodaj dokumenat</label>
				<input type="file" name="fileToUpload" id="fileToUpload">
				{!! ($errors->has('fileToUpload') ? $errors->first('fileToUpload', '<p class="text-danger">:message</p>') : '') !!}
				
			</div>
			<div class="form-group">
				<label>Naziv dokumenta</label><input type="text" class="form-control" name="file_name" id="file_name" value="">
			</div> --}}
			{{ csrf_field() }}
			{{ method_field('PUT') }}
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