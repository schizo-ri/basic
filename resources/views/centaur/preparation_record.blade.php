          
<p class="tr preparation_record_list">
    <span class="td text_preparation file_input"></span>
    <span class="td text_preparation project_no_input"></span>
    <span class="td text_preparation name_input"></span>
    <span class="td text_preparation delivery_input"></span>
    <span class="td text_preparation manager_input"></span>
    <span class="td text_preparation designed_input"></span>
    <span class="td text_preparation date_input">{{ date('d.m.Y', strtotime($record->date)) }}</span> 
    @if($record->preparation && json_decode($record->preparation))
        <span class="td text_preparation preparation_input" {!! Sentinel::inRole('voditelj') ||  Sentinel::inRole('projektant') ? 'hidden' : '' !!}  >
            @foreach(json_decode($record->preparation) as $key => $preparation1)
                <span >{{ $key . ': '}}<b>{{ $preparation1 }}</b></span>
            @endforeach
        </span>
    @else
        <span class="td text_preparation preparation_input " {!! Sentinel::inRole('voditelj') ||  Sentinel::inRole('projektant')  ? 'hidden' : '' !!}  >{{ $record->preparation }}</span>
    @endif
    @if($record->mechanical_processing && json_decode($record->mechanical_processing))
        <span class="td text_preparation mechanical_input" {!! Sentinel::inRole('voditelj') ||  Sentinel::inRole('projektant')  ? 'hidden' : '' !!} >
        @foreach(json_decode($record->mechanical_processing) as $key => $mechanical)
            <span >{{ $key . ': '}}<b>{{ $mechanical }}</b></span>
        @endforeach
        </span>
    @else
        <span class="td text_preparation mechanical_input " {!! Sentinel::inRole('voditelj') ||  Sentinel::inRole('projektant')  ? 'hidden' : '' !!} >
            {{ $record->mechanical_processing }}</span>
    @endif
    @if($record->marks_documentation && json_decode($record->marks_documentation))
        <span class="td text_preparation marks_input" {!! Sentinel::inRole('voditelj') ||  Sentinel::inRole('projektant')  ? 'hidden' : '' !!} >
            @foreach(json_decode($record->marks_documentation) as $key => $mark)
                <span >{{ $key . ': '}}<b>{{ $mark }}</b></span>
            @endforeach
        </span>
    @else
        <span class="td text_preparation marks_input " {!! Sentinel::inRole('voditelj') ||  Sentinel::inRole('projektant')  ? 'hidden' : '' !!} >
        {{ $record->marks_documentation }}</span>
    @endif
    <span class="td text_preparation equipment_input"></span>
    <span class="td text_preparation history_input"></span>
    <span class="td text_preparation option_input">
        @if (Sentinel::inRole('administrator'))
            <a href="#" class="btn btn-edit" title="Ispravi">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </a>
            <a href="{{ route('preparation_records.destroy', $record->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="Obriši">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a>
        @endif
    </span>
</p>
<form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparation_records.update', $record->id) }}" >
    <span class="input_preparation file_input"></span>
    <span class="input_preparation project_no_input"></span>
    <span class="input_preparation name_input"></span>
    <span class="input_preparation delivery_input"></span>
    <span class="input_preparation manager_input"></span>
    <span class="input_preparation designed_input"></span>
    <span class="input_preparation date_input">{{ date('d.m.Y', strtotime($record->date)) }}</span>
    <span class="input_preparation preparation_input">
        @foreach($priprema as $key1 => $priprema1) 
            <h5>{{ $priprema1 }}</h5>
            <input type="hidden" name="preparation_title[{{ $key1 }}]" value="{{ $priprema1 }}"   >
            <span class="col-md-4">
                <input type="radio" name="preparation[{{ $key1 }}]" value="DA" {!! json_decode($record->preparation,true)[$priprema1]  == 'DA' ? 'checked' : '' !!} />
                <label >DA</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="preparation[{{ $key1 }}]" value="NE" {!! json_decode($record->preparation,true)[$priprema1] == 'NE' || ( json_decode($preparation->preparation,true)[$priprema1] != 'DA' ||  json_decode($preparation->preparation,true)[$priprema1] != 'N/A' ) ? 'checked' : '' !!}  />
                <label >NE</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="preparation[{{ $key1 }}]" value="N/A" {!!  json_decode($record->preparation,true)[$priprema1] == 'N/A' ? 'checked' : '' !!} />
                <label >N/A</label>
            </span>
           
        @endforeach
    </span>
    <span class="input_preparation mechanical_input">
        @foreach($mehanicka as $key => $meh_obrada) 
            <h5>{{ $meh_obrada }}</h5>
            <input type="hidden" name="mechanical_title[{{ $key }}]" value="{{ $meh_obrada}}"   >
            <span class="col-md-4">
                <input type="radio" name="mechanical_processing[{{ $key }}]" value="DA" {!! json_decode($record->mechanical_processing,true)[$meh_obrada] == 'DA' ? 'checked' : '' !!} /><label >DA</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="mechanical_processing[{{ $key }}]" value="NE"  {!! json_decode($record->mechanical_processing,true)[$meh_obrada] == 'NE' || ( json_decode($preparation->mechanical_processing,true)[$meh_obrada] != 'DA' ||  json_decode($preparation->mechanical_processing,true)[$meh_obrada] != 'N/A') ? 'checked' : '' !!}  /><label >NE</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="mechanical_processing[{{ $key }}]" value="N/A"  {!! json_decode($record->mechanical_processing,true)[$meh_obrada] == 'N/A' ? 'checked' : '' !!} /><label >N/A</label></span>
           
        @endforeach
    </span>
    <span class="input_preparation marks_input">
        @foreach($oznake as $key2 => $oznake1) 
            <h5>{{ $oznake1 }}</h5>
            <input type="hidden" name="marks_title[{{ $key2 }}]" value="{{ $oznake1}}"   >
            <span class="col-md-4">
                <input type="radio" name="marks_documentation[{{ $key2 }}]" value="DA" {!! json_decode($record->marks_documentation,true)[$oznake1] == 'DA' ? 'checked' : '' !!} /><label >DA</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="marks_documentation[{{ $key2 }}]" value="NE" {!! json_decode($record->marks_documentation,true)[$oznake1] == 'NE' || ( json_decode($preparation->marks_documentation,true)[$oznake1] != 'DA' ||  json_decode($preparation->marks_documentation,true)[$oznake1] != 'N/A')  ? 'checked' : '' !!} /><label >NE</label>
            </span>
            <span class="col-md-4">
                <input type="radio" name="marks_documentation[{{ $key2 }}]" value="N/A"  {!! json_decode($record->marks_documentation,true)[$oznake1] == 'N/A' ? 'checked' : '' !!} /><label >N/A</label></span>
            record
        @endforeach
    </span>
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <span class="input_preparation option_input">
        <input class="btn  btn_spremi btn-preparation" type="submit" value="&#10004; Spremi">
        <a class="btn btn-cancel2" >
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            Poništi
        </a>
    </span>
</form> 