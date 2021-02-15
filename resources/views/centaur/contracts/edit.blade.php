<div class="modal-header">
	<h3 class="panel-title">Ispravi ugovor</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('contracts.update', $contract->id) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('number')) ? 'has-error' : '' }}">
				<label>Broj ugovora</label>
				<input class="form-control"  name="number" type="text" value="{{ $contract->number }}" required maxlength="20" />
				{!! ($errors->has('number') ? $errors->first('number', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('supplier')) ? 'has-error' : '' }}">
				<label>Dobavljač</label>
				<input class="form-control"  name="supplier" type="text" value="{{ $contract->supplier }}" required maxlength="191" />
				{!! ($errors->has('supplier') ? $errors->first('supplier', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
				<label>Komentar</label>
				<textarea class="form-control"  name="comment" type="date" rows="5" required >{{ $contract->comment }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="Spremi">
		</fieldset>
	</form>
</div>