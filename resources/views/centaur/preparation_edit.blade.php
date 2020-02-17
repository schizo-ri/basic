<form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.update', $preparation->id) }}" >
    <span class="input_preparation file_input"></span>

    <span class="input_preparation project_no_input">
        <input  name="project_no" type="text" value="{{ $preparation->project_no }}" maxlength="10" required autofocus {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!} />
    </span>
    <span class="input_preparation name_input">
        <input class=""  name="name" type="text" value="{{ $preparation->name }}" maxlength="100"  {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!}  />
    </span>
    <span class="input_preparation delivery_input">
        <input class="" name="delivery" type="date" {!! Sentinel::inRole('subscriber') ? 'readonly ' : '' !!} value="{{ $preparation->delivery }}" />
    </span>
    <span class="input_preparation manager_input">
        <select name="project_manager" class="project_manager" required {!! Sentinel::inRole('subscriber') ? 'readonly ' : ''  !!}>
            <option disabled selected >Voditelj projekta</option>
            @foreach ($users as $user)
                @if ($user->first_name && $user->last_name)
                    <option value="{{ $user->id }}" {!! $user->id  == $preparation->project_manager ? 'selected' : '' !!}>{{ $user->first_name . ' ' .  $user->last_name}}</option>
                @endif  
            @endforeach
        </select>
    </span>
    <span class="input_preparation designed_input">
        <select name="designed_by" class="designed_by" required {!! Sentinel::inRole('subscriber') ? 'readonly ' : '' !!}>
            <option disabled selected >Projektant</option>
            @foreach ($users as $user)
            @if ($user->first_name && $user->last_name)
                    <option value="{{ $user->id }}" {!! $user->id  == $preparation->designed_by ? 'selected' : '' !!}>{{ $user->first_name . ' ' .  $user->last_name}}</option>
                @endif                    
            @endforeach
        </select>
    </span>
    <span class="input_preparation date_input"></span>
    <span class="input_preparation preparation_input">
        <textarea name="preparation" cols="30" rows="3" placeholder="Priprema..." {!! Sentinel::inRole('moderator') ? 'readonly ' : '' !!} >{!! $preparationRecord_today ? $preparationRecord_today->preparation : '' !!}</textarea>
    </span>
    <span class="input_preparation mechanical_input">
        <textarea name="mechanical_processing" cols="30" rows="3" placeholder="Mehanička obrada ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!}  >{!!  $preparationRecord_today ? $preparationRecord_today->mechanical_processing : '' !!}</textarea>
    </span>
    <span class="input_preparation marks_input">
        <textarea name="marks_documentation" cols="30" rows="3" placeholder="Oznake i dokumentacija ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!} >{!! $preparationRecord_today ? $preparationRecord_today->marks_documentation : '' !!}</textarea>
    </span>
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <span class="input_preparation equipment_input"></span>
    <span class="input_preparation history_input"></span>
    <span class="input_preparation option_input">
        <input class="btn btn_spremi btn-preparation" type="submit" value="&#10004;" title="Ispravi">
        <a class="btn btn-cancel" >
            <span class="glyphicon glyphicon-remove" aria-hidden="true" title="Poništi"></span>
        </a>
    </span>
</form>
