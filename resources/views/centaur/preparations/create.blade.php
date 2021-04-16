<div class="modal-header">
	<h3 class="panel-title">Prebaci ormar u proizvodnju</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.store') }}" enctype="multipart/form-data">
		<fieldset>
			<input type="hidden" name="designing_id" value="{{ $designing_id }}">
			<div class="form-group {{ ($errors->has('delivery')) ? 'has-error' : '' }}">
				<label>Datum isporuke</label>
				<input class="form-control" name="delivery" type="date" value="{{ old('delivery') }}" required />
				{!! ($errors->has('delivery') ? $errors->first('delivery', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('fileToUpload')) ? 'has-error' : '' }}">
				<label>Dodaj dokumenat</label>
				<input type="file" name="file" id="fileToUpload" required>
				{!! ($errors->has('fileToUpload') ? $errors->first('fileToUpload', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<input type="hidden" class="form-control" name="file_name" id="file_name" value="">
				<input type="checkbox" name="siemens" value="1" id="siemens"><label for="siemens"> Upload Siemens</label>
			</div>
			

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