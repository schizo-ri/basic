<div class="modal-header">
	<h3 class="panel-title">Novi artikl</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('stocks.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('product_number')) ? 'has-error' : '' }}">
				<label>Produkt</label>
				<input class="form-control"  name="product_number" type="text" value="{{ old('product_number') }}" maxlength="50" required />
				{!! ($errors->has('product_number') ? $errors->first('product_number', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('manufacturer_id')) ? 'has-error' : '' }}">
				<label>Proizvođač</label>
				<select class="form-control" name="manufacturer_id" id="manufacturer_id" required>
					<option value="" disabled selected></option>
					@foreach ($manufacturers as $manufacturer)
						<option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('manufacturer_id') ? $errors->first('manufacturer_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label>Naziv</label>
				<input class="form-control" name="name" type="text" value="{{ old('name') }}" required maxlength="191"  />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('price')) ? 'has-error' : '' }}">
				<label>Cijena</label>
				<input class="form-control" name="price" type="number" step="0.01" value="{{ old('price') }}" required />
				{!! ($errors->has('price') ? $errors->first('price', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('quantity')) ? 'has-error' : '' }}">
				<label>Količina</label>
				<input class="form-control"  name="quantity" type="number" step="0.01" value="{{ old('quantity') }}"  />
				{!! ($errors->has('quantity') ? $errors->first('quantity', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('unit')) ? 'has-error' : '' }}">
				<label>Jedinica mjere</label>
				<input class="form-control"  name="unit" type="text" maxlength="10" value="{{ old('unit') }}"  />
				{!! ($errors->has('unit') ? $errors->first('unit', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Spremi">
		</fieldset>
	</form>
</div>