@extends('Centaur::layout')

@section('title', 'Projektiranje')

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
            <a href="{{ route('designings.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        </div>
        <h1>Projektiranje
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover" id="index_table">
                    <thead>
                        <tr>
                            <th>Broj</th>
                            <th>Naziv</th>
                            <th>Datum isporuke</th>
                            <th>Voditelj</th>
                            <th>Projektant</th>
                            <th>Napomena</th>
                            <th>Dokumenti</th>
                            <th>Opcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $j=0;
                            $count = count($designings) - 3;
                        @endphp
                        @if(count($designings) > 0)
                            @foreach ($designings as $designing)
                            @php
                                $employee = $designing->designer;
                            @endphp
                                <tr class="{!! $j>3 && $j>=$count ? 'align_top' : '' !!}">
                                    <td>{{ $designing->project_no }}</td>
                                    <td>{{ $designing->name  }}</td>
                                    <td>{{ date('d.m.Y', strtotime($designing->date) ) }}</td>
                                    <td>{{ $designing->manager->first_name . ' ' . $designing->manager->last_name  }}</td>
                                    <td>
                                        <form class="update_preparation_employee" accept-charset="UTF-8" role="form" method="post" action="{{ route('designings.update', $designing->id) }}" id="{{ $designing->id }}" >
                                            <fieldset>
                                                <div class="selectBox showCheckboxes" >
                                                    <label class="zaduzi_text">
                                                        @if (! $employee )
                                                            @if ( Sentinel::inRole('administrator'))
                                                                Zaduži projektanta
                                                            @endif
                                                        @else
                                                            {{ $employee->first_name . ' ' . trim($employee->last_name) }}
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
                                                                <input name="designer_id" type="radio" id="id_{{ $j }}_{{ $i }}_{{ $user->id }}" value="{{ $user->id }}" {!! $employee->id == $user->id ? 'checked' : '' !!} />
                                                                <label for="id_{{ $j }}_{{ $i }}_{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</label>
                                                            </label>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach
                                                        <input type="hidden" name="designing_id" value="{{  $designing->id }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PUT') }}
                                                        <input class="btn  btn_spremi store_preparation" type="submit" value="Spremi">
                                                    </div>
                                                @endif
                                            </fieldset>
                                        </form>
                                    </td>
                                    <td>{{ $designing->comment  }}</td>
                                    <td><span class="open_doc_list">
                                        @php
                                            $docs = array();
                                            $path = 'uploads/' . $designing->id . '/';
                                            if(file_exists($path)){
                                                $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
                                            } 
                                        @endphp
                                        @if (count( $docs)>0)
                                        <label class="collapsible ">Dokumenti  <i class="fas fa-caret-down"></i></label>
                                        <div class="content">
                                            @foreach ($docs as $doc)
                                                <p>{{ $doc }} 
                                                    <a href="{{ action('DesigningController@delete_file' ) }}" class="action_confirm btn-delete doc danger" data-token="{{ csrf_token() }}" title="{{$path . $doc }}">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </p>
                                            @endforeach
                                        </div>
                                            
                                        @endif
                                       
                                    </span></td>
                                    <td>
                                        <a href="{{ route('designings.edit', $designing->id) }}" class="btn " rel="modal:open">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            Edit
                                        </a>
                                        <a href="{{ route('designings.destroy', $designing->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            Delete
                                        </a>
                                        
                                        
                                    </td>
                                </tr>
                                @php
                                    $j++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>	
    $.getScript('/../js/filter.js');
    $.getScript('/../js/desinings.js');
</script>
@stop
