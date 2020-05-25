<form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.update', $preparation->id) }}" >
    <span class="input_preparation file_input"></span>
    <span class="input_preparation project_no_input">
        <input  name="project_no" type="text" value="{{ $preparation->project_no }}" maxlength="30" required autofocus {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!} />
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
       
        @foreach($priprema as $key1 => $priprema1) 
            <h5>{{ $priprema1 }}</h5>
            <input type="hidden" name="preparation_title[{{ $key1 }}]" value="{{ $priprema1 }}"   >
            <span class="col-md-4">
                <input type="radio" name="preparation[{{ $key1 }}]" value="DA" {!! json_decode($preparation->preparation,true)[$priprema1]  == 'DA' ? 'checked' : '' !!} /><label >DA</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="preparation[{{ $key1 }}]" value="NE"  {!! json_decode($preparation->preparation,true)[$priprema1]  == 'NE' || ( json_decode($preparation->preparation,true)[$priprema1] != 'DA' ||  json_decode($preparation->preparation,true)[$priprema1] != 'N/A' ) ? 'checked' : '' !!}  /><label >NE</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="preparation[{{ $key1 }}]" value="N/A"  {!!  json_decode($preparation->preparation,true)[$priprema1] == 'N/A'  ? 'checked' : '' !!} /><label >N/A</label></span>
           
        @endforeach
    </span>
    <span class="input_preparation mechanical_input">
        @foreach($mehanicka as $key => $meh_obrada) 
            <h5>{{ $meh_obrada }}</h5>
            <input type="hidden" name="mechanical_title[{{ $key }}]" value="{{ $meh_obrada}}"   >
            <span class="col-md-4">
                <input type="radio" name="mechanical_processing[{{ $key }}]" value="DA" {!! json_decode($preparation->mechanical_processing,true)[$meh_obrada] == 'DA' ? 'checked' : '' !!} /><label >DA</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="mechanical_processing[{{ $key }}]" value="NE"  {!! json_decode($preparation->mechanical_processing,true)[$meh_obrada] == 'NE' || ( json_decode($preparation->mechanical_processing,true)[$meh_obrada] != 'DA' ||  json_decode($preparation->mechanical_processing,true)[$meh_obrada] != 'N/A' ) ? 'checked' : '' !!}  /><label >NE</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="mechanical_processing[{{ $key }}]" value="N/A" {!! json_decode($preparation->mechanical_processing,true)[$meh_obrada] == 'N/A' ? 'checked' : '' !!} /><label >N/A</label></span>
           
        @endforeach
    </span>
    <span class="input_preparation marks_input">
        @foreach($oznake as $key2 => $oznake1) 
            <h5>{{ $oznake1 }}</h5>
            <input type="hidden" name="marks_title[{{ $key2 }}]" value="{{ $oznake1}}"   >
            <span class="col-md-4">
                <input type="radio" name="marks_documentation[{{ $key2 }}]" value="DA" {!! json_decode($preparation->marks_documentation,true)[$oznake1] == 'DA' ? 'checked' : '' !!} /><label >DA</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="marks_documentation[{{ $key2 }}]" value="NE"  {!! json_decode($preparation->marks_documentation,true)[$oznake1] == 'NE'  || ( json_decode($preparation->marks_documentation,true)[$oznake1] != 'DA' ||  json_decode($preparation->marks_documentation,true)[$oznake1] != 'N/A' )  ? 'checked' : '' !!}  /><label >NE</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="marks_documentation[{{ $key2 }}]" value="N/A"  {!! json_decode($preparation->marks_documentation,true)[$oznake1] == 'N/A' ? 'checked' : '' !!} /><label >N/A</label></span>
            
        @endforeach
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
