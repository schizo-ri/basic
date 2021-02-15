 <div class="modal-header">
	<h3 class="panel-title">Dodaj stanicu</h3>
</div>
<div class="modal-body">
    <form class="" accept-charset="UTF-8" role="form" method="post" action="{{ route('agglomeration_stations.store') }}">
        <input name="agglomeration_id" type="text" value="{{ $agglomeration_id }}" required hidden />
        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            <label>Naziv</label>
            <input class="form-control"  name="name" type="text" value="{{ old('name') }}" required maxlength="100" />
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>       
        <div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
            <label>Komentar</label>
            <textarea class="form-control" name="comment" type="text" maxlength="191" rows="5"></textarea>
            {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ csrf_field() }}
        <input class="btn btn_spremi submit_createForm float_right" {{-- disabled --}} type="submit" value="&#10004; Spremi">
    </form>
</div>