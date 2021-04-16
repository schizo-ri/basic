<div class="modal-header">
    <h3 class="panel-title">Razduži sa skladišta</h3>
</div>
<div class="modal-body">
    <form class="" accept-charset="UTF-8" role="form" method="post" action="{{ route('discharge_stocks.store') }}">
        <div class="form-group {{ ($errors->has('preparation_id')) ? 'has-error' : '' }}">
            <label>Projekt</label>
            <select name="preparation_id" id="preparation_id" class="form-control" value="{{ old('preparation_id') }}">
                <option value="" selected disabled></option>
                @foreach ($preparations as $preparation)
                    <option value="{{ $preparation->id }}" >{{ $preparation->project_no . ' ' . $preparation->project_name . ' ' . $preparation->name}}</option>
                @endforeach
            </select>
            {!! ($errors->has('preparation_id') ? $errors->first('preparation_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('item_id')) ? 'has-error' : '' }}">
            <label>Produkt</label>
            <select name="item_id" id="item_id" class="form-control" value="{{ old('item_id') }}">
                <option value="" disabled ></option>
                @foreach ($stocks as $item)
                    <option value="{{ $item->id }}" {!! isset( $stock_item ) && $stock_item->id == $item->id ? 'selected' : '' !!}>{{ $item->product_number . ' ' . $item->name }}</option>
                @endforeach
            </select>
            {!! ($errors->has('item_id') ? $errors->first('item_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('quantity')) ? 'has-error' : '' }}">
            <label>Količina</label>
            <input class="form-control"  name="quantity" type="number" step="0.01" max="{{ $stock_item->quantity - $stock_item->hasDischarges->sum('quantity') }}" value="{{ old('quantity') }}" autocomplete="off"  />
            {!! ($errors->has('quantity') ? $errors->first('quantity', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
            <label>Komentar</label>
            <textarea class="form-control"  name="comment" type="text" maxlength="191">{{ old('comment') }}</textarea>
            {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        @if (Sentinel::inRole('administrator'))
            <div class="form-group {{ ($errors->has('missing')) ? 'has-error' : '' }}">
                <p class="proj_cat">
                    <input type="checkbox" id="missing_1" name="missing" value="1" /> 
                    <label for="missing_1">Nedostaje</label>
                </p>
            </div>
            <div class="form-group {{ ($errors->has('damaged')) ? 'has-error' : '' }}">
                <p class="proj_cat">
                    <input type="checkbox" id="damaged_1" name="damaged" value="1" /> 
                    <label for="damaged_1">Neispravno</label>
                </p>
            </div>
        @endif
        {{ csrf_field() }}
        <input class="btn btn_spremi submit_createForm float_right" {{-- disabled --}} type="submit" value="&#10004; Spremi">
    </form>
</div>
<script>
   /*  $.getScript( '/../js/manufacturer.js'); */
    
</script>