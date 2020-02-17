          
<p class="tr preparation_record_list">
    <span class="td text_preparation file_input"></span>
    <span class="td text_preparation project_no_input"></span>
    <span class="td text_preparation name_input"></span>
    <span class="td text_preparation delivery_input"></span>
    <span class="td text_preparation manager_input"></span>
    <span class="td text_preparation designed_input"></span>
    <span class="td text_preparation date_input">{{ date('d.m.Y', strtotime($record->date)) }}</span> 
    <span class="td text_preparation preparation_input wrap" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!}  >{{ $record->preparation }}</span>
    <span class="td text_preparation mechanical_input wrap" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!} >{{ $record->mechanical_processing }}</span>
    <span class="td text_preparation marks_input wrap" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!} >{{ $record->marks_documentation }}</span>
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
        <textarea name="preparation" cols="30" rows="3" placeholder="Priprema..." {!! Sentinel::inRole('moderator') ? 'readonly ' : '' !!} >{{ $record->preparation }}</textarea>
    </span>
    <span class="input_preparation mechanical_input">
        <textarea name="mechanical_processing" cols="30" rows="3" placeholder="Mehanička obrada ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!}  >{{ $record->mechanical_processing }}</textarea>      
    </span>
    <span class="input_preparation marks_input">
        <textarea name="marks_documentation" cols="30" rows="3" placeholder="Oznake i dokumentacija ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!} >{{ $record->marks_documentation }}</textarea>
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