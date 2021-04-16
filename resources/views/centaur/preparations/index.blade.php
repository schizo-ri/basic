@extends('Centaur::layout')

@section('title', 'Proizvodnja')

@section('content')
<div class="preparation">
    <span hidden class="today">{{ date('Y-m-d') }}</span>
    <div class="page-header">
        <div class="page_navigation pull-left">
            <span class="pull-left" >Proizvodnja</span>
        </div>
      {{--   <div style="float:right"><span class="alert alert-danger" style="display: block; margin: 0;">Molim obrisati cache sa ctrl+f5 da se povuće novi dizajn</span></div> --}}
        <div class='btn-toolbar pull-right'>
            <span class="show_inactive"><a href="{{ route('preparations.index', ['active' => $active == 1 ? 0 : 1]) }}">{!!  $active == 1 ? 'Prikaži neaktivne' : 'Prikaži aktivne' !!}</a></span>
            <label class="filter_empl">
                <select class="select_employee" name="select_employee" >
                    <option value="all">Svi</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->first_name . ' ' . $user->last_name }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." id="mySearch_preparation">
                <i class="clearable__clear">&times;</i>
            </label>
            
             <!--  <a href="{{ route('preparations.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>-->
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <div class="table table-hover table_preparations preparation_list" id="index_table">
                    <div class="col-xs-12">
                        <p class="tr">
                            <span class="col-xs-10">
                                <span class="col-md-1">Broj</span>
                                <span class="col-md-3">Naziv projekta</span>
                                <span class="col-md-3">Naziv ormara</span>
                                <span class="col-md-2">Voditelj projekta</span>
                                <span class="col-md-2">Projektirao</span>
                                <span class="col-md-1">Datum isporuke</span>
                            </span>
                            <span class="col-xs-2">
                                <span class="col-md-12"> Zaduženi za pripremu i označavanje</span>
                            </span>
                        </p>
                    </div>
                    @php
                        $j=0;
                        $count = count($preparations) - 3;
                    @endphp
                    <div class="col-xs-12">
                        @foreach ($preparations as $proj_no => $preparation1)
                            @php
                                $preparation_name ='';
                                foreach ($preparation1 as $preparation) {
                                    $preparation_name .= $preparation->name . ',';
                                }
                                $preparation_name = rtrim($preparation_name,",");
                                $employees = $preparation1->first()->employees;
                            @endphp
                            @if( $proj_no )
                                <div class="tr open_project col-xs-12 {!! $j>=$count ? 'align_top' : '' !!}">
                                    <a href="{{ route('preparations.show', $preparation1->first()->id) }}" class="show_preparations col-xs-10">
                                        <span class="col-md-1 {!! $preparation1->where('finish',1)->first() ? 'bg_yellow' : '' !!} ">
                                            {{ $proj_no  }}  
                                        </span>
                                        <span class="col-md-3">
                                            {{$preparation1->first()->project_name}}
                                        </span>
                                        <span class="col-md-3">
                                            {{$preparation_name }}
                                        </span>
                                        <span class="col-md-2">{{ $preparation1->first()->manager['first_name'] . ' ' . $preparation1->first()->manager['last_name'] }}</span>
                                        <span class="col-md-2">{{ $preparation1->first()->designed['first_name'] . ' ' . $preparation1->first()->designed['last_name'] }}</span>
                                        
                                        <span class="col-md-1">{{ date("d.m.Y",strtotime($preparation1->sortBy('delivery')->first()->delivery)) }}</span>
                                    </a>
                                    <div class="col-xs-2 td">
                                        <form class="update_preparation_employee" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparation_employees.update', $preparation1->first()->id) }}" id="{{ $preparation->id }}" >
                                            <fieldset>
                                                <div class="selectBox showCheckboxes" >
                                                    <label class="zaduzi_text">
                                                        @if (count($employees) == 0 && Sentinel::inRole('administrator'))
                                                            Zaduži za pripremu
                                                        @else
                                                            @foreach ( $employees as $key => $employee )
                                                            {!! $key!=0?'| ':''!!}{{ $employee->user->first_name . ' ' . trim($employee->user->last_name) }}
                                                            @endforeach
                                                        @endif
                                                        @if ( Sentinel::inRole('administrator'))
                                                            <i class="fas fa-caret-down"></i>
                                                        @endif
                                                    </label>
                                                    <div class="overSelect"></div>
                                                </div>
                                                @if ( Sentinel::inRole('administrator'))
                                                    <div class="checkboxes1" >
                                                        {{-- <input type="search"  placeholder="{{ __('basic.search')}}"  id="mySearch1"> --}}
                                                        @php
                                                            $i = 0;
                                                        @endphp
                                                        @foreach ($users as $user)
                                                            <label  class="col-12 float_left panel1" >
                                                                <input name="user_id[]" type="checkbox" id="id_{{ $j }}_{{ $i }}_{{ $user->id }}" value="{{ $user->id }}" {!! $employees->where('user_id',  $user->id )->first() ? 'checked' : '' !!} />
                                                                <label for="id_{{ $j }}_{{ $i }}_{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</label>
                                                            </label>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach
                                                        <input type="hidden" name="preparation_id" value="{{  $preparation1->first()->id }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PUT') }}
                                                        <input class="btn  btn_spremi store_preparation" type="submit" value="Spremi">
                                                    </div>
                                                @endif
                                            </fieldset>
                                        </form>
                                     </div>
                                </div>
                            @endif
                            @php
                                $j++;
                            @endphp
                        @endforeach
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
         <!-- Novi unos -->
         @if(Sentinel::getUser()->hasAccess(['preparations.create']))
            @include('centaur.preparation_create')
        @endif
    </div>
   

</div>
<script>
    $.getScript('/../js/preparation.js');
</script>
@stop