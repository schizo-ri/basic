@extends('Centaur::layout')

@section('title', 'Priprema i mehanička obrada')

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearch_preparation()" id="mySearch_preparation">
                <i class="clearable__clear">&times;</i>
            </label>
          <!--  <a href="{{ route('preparations.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>-->
        </div>
        <h1>Priprema i mehanička obrada</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <div class="table table-hover" id="index_table">
                    <div class="thead">
                        <p class="tr">
                            <span class="th" >Broj</span>
                            <span class="th">Naziv</span>
                            <span class="th">Priprema</span>
                            <span class="th">Mehanička obrada</span>
                            @if (Sentinel::inRole('administrator'))
                                <span class="td" >Opcije</span>
                            @endif
                        </p>
                    </div>
                    <div class="tbody">
                        @foreach ($preparations as $preparation)
                            <form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.update', $preparation->id) }}" >
                                <span class="input_preparation">
                                    <input  name="project_no" type="text" value="{{ $preparation->project_no }}" required autofocus />
                                </span>
                                <span class="input_preparation">
                                    <input class="input_preparation"  name="name" type="text" value="{{ $preparation->name }}" />
                                </span>
                                <span class="input_preparation">
                                    <input class="input_preparation"  name="preparation" type="text" value="{{ $preparation->preparation }}" />
                                </span>
                                <span class="input_preparation">
                                    <input class="input_preparation"  name="mechanical_processing" type="text" value="{{$preparation->mechanical_processing  }}" />
                                </span>
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
                            <p class="tr row_preparation_text">
                                <span class="td text_preparation">{{ $preparation->project_no  }}</span>
                                <span class="td text_preparation">{{ $preparation->name }}</span>
                                <span class="td text_preparation">{{ $preparation->preparation }}</span>
                                <span class="td text_preparation">{{ $preparation->mechanical_processing  }}</span>
                                <span class="td">
                                    <a href="#" class="btn btn-edit">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        Ispravi
                                    </a>
                                    @if (Sentinel::inRole('administrator'))   
                                        <a href="{{ route('preparations.destroy', $preparation->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                            Obriši
                                        </a>
                                    @endif
                                </span>
                            </p>
                        @endforeach
                    
                        <form accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.store') }}">
                            <span class="input_preparation">
                                <input  name="project_no" type="text" value="{{ old('project_no') }}" required autofocus />
                            </span>
                            <span class="input_preparation">
                                <input class="input_preparation"  name="name" type="text" value="{{ old('name') }}" />
                            </span>
                            <span class="input_preparation">
                                <input class="input_preparation"  name="preparation" type="text" value="{{ old('preparation') }}" />
                            </span>
                            <span class="input_preparation">
                                <input class="input_preparation"  name="mechanical_processing" type="text" value="{{ old('mechanical_processing') }}" />
                            </span>
                            {{ csrf_field() }}
                            <span class="input_preparation">
                                <input class="btn btn_spremi" type="submit" value="&#10004; Spremi">
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $('a.btn-edit').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().prev('.form_preparation').show();
        $(this).parent().parent().hide();

    });
    $('a.btn-cancel').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().next('.row_preparation_text').show();
        $(this).parent().parent().hide();

    });
    
    $.getScript('/../js/filter.js');
</script>
@stop
