<form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.update', $preparation->id) }}" >
    <span class="input_preparation file_input"></span>

    <span class="input_preparation project_no_input">
        <input  name="project_no" type="text" value="{{ $preparation->project_no }}" maxlength="10" required autofocus {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!} />
    </span>
    <span class="input_preparation name_input">
        <input class=""  name="name" type="text" value="{{ $preparation->name }}" maxlength="100"  {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!}  />
    </span>
    <span class="input_preparation delivery_input">
        @if (!Sentinel::inRole('subscriber'))
            <input class="" name="delivery" type="date" value="{{ $preparation->delivery }}" />
        @endif
    </span>
    <span class="input_preparation manager_input">
        @if (!Sentinel::inRole('subscriber'))
        <select name="project_manager" class="project_manager" required>
            <option disabled selected >Voditelj projekta</option>
            @foreach ($users as $user)
                @if ($user->first_name && $user->last_name)
                    <option value="{{ $user->id }}"  {!! $user->id  == $preparation->project_manager ? 'selected' : '' !!}>{{ $user->first_name . ' ' .  $user->last_name}}</option>
                @endif                    
            @endforeach
        </select>
        @endif
    </span>
    <span class="input_preparation designed_input">
        @if (!Sentinel::inRole('subscriber'))
        <select name="designed_by" class="designed_by" required>
            <option disabled selected >Projektant</option>
            @foreach ($users as $user)
            @if ($user->first_name && $user->last_name)
                    <option value="{{ $user->id }}" {!! $user->id  == $preparation->designed_by ? 'selected' : '' !!}>{{ $user->first_name . ' ' .  $user->last_name}}</option>
                @endif                    
            @endforeach
        </select>
        @endif
    </span>
    <span class="input_preparation preparation_input">
        @if (! Sentinel::inRole('moderator'))
            <input class=""  name="preparation" type="text" value="{!! $preparationRecord_today ? $preparationRecord_today->preparation : '' !!}" maxlength="255" placeholder="Priprema..."  {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!}  />
        @endif
    </span>
    <span class="input_preparation mechanical_input">
        @if (! Sentinel::inRole('moderator'))
            <input class=""  name="mechanical_processing" type="text" value="{!! $preparationRecord_today ? $preparationRecord_today->mechanical_processing : '' !!}" placeholder="Mehanička obrada..."  maxlength="255" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!} />
        @endif    
    </span>
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <span class="input_preparation option_input">
        <input class="btn btn_spremi btn-preparation" type="submit" value="&#10004;" title="Ispravi">
        <a class="btn btn-cancel" >
            <span class="glyphicon glyphicon-remove" aria-hidden="true" title="Poništi"></span>
        </a>
    </span>
</form>
