<form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.update', $preparation->id) }}" >
    <span class="input_preparation">
        <input  name="project_no" type="text" value="{{ $preparation->project_no }}" maxlength="10" required autofocus {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!} />
    </span>
    <span class="input_preparation">
        <input class="input_preparation"  name="name" type="text" value="{{ $preparation->name }}" maxlength="100" {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!}  />
    </span>
    <span class="input_preparation">
        <input class="input_preparation"  name="date" type="date" value="{{ date('Y-m-d') }}"  readonly/>
    </span>
    @if (! Sentinel::inRole('moderator'))
        <span class="input_preparation">
            <input class="input_preparation"  name="preparation" type="text" value="{!! $preparationRecord_today ? $preparationRecord_today->preparation : '' !!}" maxlength="255"  {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!}  />
        </span>
        <span class="input_preparation">
            <input class="input_preparation"  name="mechanical_processing" type="text" value="{!! $preparationRecord_today ? $preparationRecord_today->mechanical_processing : '' !!}" maxlength="255" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!} />
        </span>
    @endif
    
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <span class="input_preparation">
        <input class="btn  btn_spremi btn-preparation" type="submit" value="&#10004; Spremi">
        <a class="btn btn-cancel" >
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                Poništi
            </a>
    </span>
</form>