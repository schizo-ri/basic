<div class="modal-header">
    <h3 class="panel-title">Ispravi proizvođača</h3>
</div>
<div class="modal-body">
    <form class="manufacturer_store" accept-charset="UTF-8" role="form" method="post" action="{{ route('manufacturers.update', $manufacturer->id) }}">
        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            <label>Naziv</label>
            <input class="form-control"  name="name" type="text" value="{{  $manufacturer->name }}" required maxlength="50" autofocus/>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="btn btn_spremi submit_createForm float_right" type="submit" value="&#10004; Spremi">
    </form>
</div>
<script>
   $.getScript( '/../js/manufacturer.js');
</script>