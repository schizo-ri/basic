<div class="modal-header">
	<h3 class="panel-title">Dodaj aglomeraciju</h3>
</div>
<div class="modal-body">
    <form class="" accept-charset="UTF-8" role="form" method="post" action="{{ route('agglomerations.store') }}">
        <input name="contract_id" type="text" value="{{ $contract_id }}" required hidden />
        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            <label>Naziv</label>
            <input class="form-control"  name="name" type="text" value="{{ old('name') }}" required maxlength="50" />
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('manager')) ? 'has-error' : '' }}">
            <label>Voditelj</label>
            <select name="manager" class="form-control" value="{{ old('manager') }}" required >
                <option disabled selected ></option>
                @foreach ($voditelji as $user)
                    @if ($user->first_name && $user->last_name)
                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->last_name}}</option>
                    @endif                    
                @endforeach
            </select>
            {!! ($errors->has('manager') ? $errors->first('manager', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('designer')) ? 'has-error' : '' }}">
            <label>Projektant</label>
            <select name="designer" class="form-control" value="{{ old('designer') }}" required >
                <option disabled selected ></option>
                @foreach ($projektanti as $user)
                    @if ($user->first_name && $user->last_name)
                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->last_name}}</option>
                    @endif                    
                @endforeach
            </select>
            {!! ($errors->has('designer') ? $errors->first('designer', '<p class="text-danger">:message</p>') : '') !!}
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