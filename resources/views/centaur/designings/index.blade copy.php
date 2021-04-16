@extends('Centaur::layout')

@section('title', 'Projektiranje')
@php
  // $num_of_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
    $num_of_days = 33;
    $num_of_days_m = $num_of_days;
    $num_of_days_w = $num_of_days;
    /*  $today_m = new Datetime('now');*/
  
   $today_d = new Datetime('now');
   $modify = $today_d->format('N')-1 + 7;
   $today_d->modify('-'.$modify. 'days');

   $today_w = new Datetime('now'); 
   $today_w->modify('-'.$modify. 'days');
   setlocale(LC_TIME, 'HR.utf8');
@endphp
@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <span class="show_inactive"><a href="{{ route('designings.index', ['active' => $active == 1 ? 0 : 1]) }}">{!!  $active == 1 ? 'Prikaži neaktivne' : 'Prikaži aktivne' !!}</a></span>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
        </div>
        <h1>Projektiranje</h1>
        <span hidden class="status">{{ $active }}</span>
    </div>
    <div class="">
        <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 timeline">
            <div class="table-responsive">
                <span class="next_week"><i class="fas fa-chevron-right"></i></span>
                <span class="previous_week"><i class="fas fa-chevron-left"></i></span>
                <table class="table table-hover" id="">
                    <thead>
                        <tr class="week_row">
                            <th class="week_first_cell">Tjedan</th>
                            @for ($i = 0; $i < $num_of_days_w; $i++)
                                @if($today_w->format('N')<=5)
                                    <th class="align_center {!! $today_w->format('N') == 1 || $i == 0 ? 'week' : '' !!}" colspan="{{ 5 - $today_w->format('N') + 1}}" >{!! $today_w->format('N') == 1 || $i == 0 ?  $today_w->format('W') : '' !!}</th>
                                @endif
                                @php
                                    $today_w->modify('+'.(7 - $today_w->format('N')+1 ).'days');
                                    $i = $i + (7 - $today_w->format('N'));
                                @endphp
                            @endfor
                        </tr>
                        <tr class="days_row">
                            <th class="day_first_cell">Djelatnik</th>
                            @for ($i = 0; $i < $num_of_days; $i++)
                                @if($today_d->format('N')<=5)
                                    <th id="{{ $today_d->format('Y-m-d')}}" class="day align_center {!! $today_d->format('N') == 1 || $i == 0 ? 'week_first_Day' : '' !!} {!! $today_d->format('N') == 7  ? 'week_last_Day' : '' !!}">{{ $today_d->format('d.m.') }} <br> {{ strftime('%a', strtotime($today_d->format('Y-m-d'))) }} 
                                    </th>
                                @endif
                                @php
                                    $today_d->modify('+1 day');
                                @endphp
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                      {{--   @foreach ($users as $user)
                            @php
                                $today1 = new Datetime('now');
                                $today1->modify('-'.$modify. 'days');
                            @endphp
                            <tr class="user_days " id="user_{{ $user->id }}_0">
                                <td class="list_first_cell" >{!! $user->first_name . ' ' . $user->last_name !!}</td>
                                @for ($i = 0; $i <= $num_of_days; $i++)
                                    @if($today1->format('N')<=5)
                                        <td class="proj_user_color days {!! $today1->format('N') == 1 || $i == 0  ? 'week_first_Day' : '' !!} " id="{{ $user->id . '_' .$today1->format('Y-m-d') }}">
                                            
                                        </td>
                                    @endif
                                    @php
                                        $today1->modify('+1 day');
                                    @endphp
                                @endfor
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        <span class="json_users" hidden> {{ json_encode($users )}}</span>
        </section>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 designers_list ">
            <div class='btn-toolbar pull-right'>
                @if(Sentinel::getUser()->hasAccess(['designings.create']))
                    <a href="{{ route('designings.create') }}" rel="modal:open"><i class="fas fa-plus"></i> Dodaj novi projekt </a>
                @endif
                @if(count($designers) > 0)
                    <select id="designer_filter" class="select_filter filter_designer" >
                        <option value="all">Svi projektanti</option>
                        @foreach ($designers as $designer)
                            @if ( $designer)
                                <option value="{{ $designer->first_name . ' ' . $designer->last_name  }}">{{ $designer->first_name . ' ' . $designer->last_name  }}</option>
                            @endif
                        @endforeach
                    </select>
                @endif
            </div>
            <h1>Projekti</h1>
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
                            <th class="">Status</th>
                            @if(Sentinel::getUser()->hasAccess(['designings.update']) || Sentinel::getUser()->hasAccess(['designings.delete']) )
                                <th class="">Opcije</th>
                            @endif
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
                                    if ($designing->designer != null ) {
                                        $employee = $designing->designer;
                                    } else {
                                        $employee = null;
                                    }
                                @endphp
                                <tr class="project_{{ $designing->id }} {!! $j>3 && $j>=$count ? 'align_top' : '' !!}">
                                    <td>{{ $designing->project_no }}</td>
                                    <td>{{ $designing->name  }}</td>
                                    <td>{{ date('d.m.Y', strtotime($designing->date) ) }}</td>
                                    <td>{!! $designing->manager ? $designing->manager->first_name . ' ' . $designing->manager->last_name : ''  !!}</td>
                                    <td class="designer_select">
                                        <form class="update_preparation_employee" accept-charset="UTF-8" role="form" method="post" action="{{ route('designings.update', $designing->id) }}" id="{{ $designing->id }}" >
                                            <fieldset>
                                                <div class="selectBox showCheckboxes" >
                                                    <span class="j" hidden >{{ $j }}</span>
                                                    <span class="employee_id" hidden>{!! $employee ? $employee->id : null !!}</span>
                                                    <label class="zaduzi_text {!! Sentinel::inRole('administrator') ? 'cursor' : '' !!}">
                                                        @if (! isset($employee) ||  ! $employee  )
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
                                                @if(Sentinel::getUser()->hasAccess(['preparation_employees.create']))
                                                    <div class="checkboxes1" >
                                                       {{--  @php
                                                            $i = 0;
                                                        @endphp
                                                        @foreach ($users as $user)
                                                            <label  class="col-12 float_left panel1" >
                                                                <input name="designer_id" type="radio" id="id_{{ $j }}_{{ $i }}_{{ $user->id }}" value="{{ $user->id }}" {!! $employee && $employee->id == $user->id ? 'checked' : '' !!} />
                                                                <label for="id_{{ $j }}_{{ $i }}_{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</label>
                                                            </label>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach--}}
                                                        <input type="date" name="start" value="{{ $designing->start }}" title="Početni datum">
                                                        <input type="date" name="end" value="{{ $designing->end }}" title="Završni datum">
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
                                    <td>
                                        <span class="open_doc_list" >
                                            @php
                                                $docs = array();
                                                $path = 'uploads/' . $designing->id . '/';
                                                if(file_exists($path)){
                                                    $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
                                                } 
                                            @endphp
                                            @if (count( $docs)>0)
                                            <label class="collapsible ">Dokumenti  <i class="fas fa-caret-down"></i></label>
                                            <div class="content file">
                                                @foreach ($docs as $doc)
                                                    <p><a href="{{ $path . $doc }}" target="_blanck" class="open_file">{{ $doc }}</a>
                                                        @if( Sentinel::getUser()->hasAccess(['designings.delete']) )
                                                            <a href="{{ action('DesigningController@delete_file' ) }}" class="action_confirm btn-delete doc danger" data-token="{{ csrf_token() }}" title="{{$path . $doc }}">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>
                                                        @endif
                                                    </p>
                                                @endforeach
                                            </div>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="empl_color">
                                        @if ($designing->designer && $designing->start)
                                            <span class="vertical_align_m">Dodjeljeno</span> <span class="user_color" style="background-color:{{ $designing->designer->color }} "></span>
                                        @else
                                            <span class="vertical_align_m">Nedodjeljeno <span class="user_color" style="background-color:#ddd"></span></span>
                                        @endif
                                    </td>
                                    @if(Sentinel::getUser()->hasAccess(['designings.update']) || Sentinel::getUser()->hasAccess(['designings.delete']) )
                                        <td class="">
                                            @if(Sentinel::getUser()->hasAccess(['designings.update']) )
                                                <a href="{{ route('designings.show', $designing->id) }}" class="btn">
                                                    <i class="fas fa-comments"></i> Komentari
                                                </a>
                                            @endif
                                            @if(Sentinel::getUser()->hasAccess(['designings.update']) )
                                                <span class="file-upload btn" id="upload_{{ $designing->id }}"><i class="fas fa-upload"></i> Upload</span>
                                                <a href="{{ route('designings.edit', $designing->id) }}" class="btn " rel="modal:open">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                    Edit
                                                </a>
                                            @endif
                                            @if ( Sentinel::getUser()->hasAccess(['designings.create']) )
                                                <a href="{{ action('DesigningController@close_designing', $designing->id) }}" class="btn" class="action_confirm" ><i class="fas fa-check"></i>
                                                    @if ($designing->active == 1)Završi @else Vrati @endif  
                                                </a>
                                            @endif
                                            @if(Sentinel::getUser()->hasAccess(['designings.delete']) )
                                            <a href="{{ route('designings.destroy', $designing->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                Delete
                                            </a>
                                            @endif
                                        </td>
                                    @endif
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
$.getScript('/../js/designings.js');
$('.file-upload').click(function(){
    var id = $( this ).attr('id');
    id = id.replace('upload_','');
    
    $('<form accept-charset="UTF-8" role="form" method="post" action="'+location.origin+'/designings/'+id+'" enctype="multipart/form-data"><div class="form-group"><label>Dodaj dokumenat</label><input type="file" name="fileToUpload[]" id="fileToUpload" multiple></div><div class="form-group file_names"><label>Naziv dokumenta</label></div><input type="hidden" name="file_up" value="1"><input name="_token" value="{{ csrf_token() }}" type="hidden"><input name="_method" value="PUT" type="hidden"><input class="btn btn-md btn-primary pull-right" type="submit" value="Spremi"></form>').appendTo('body').modal();

    $('#fileToUpload').change(function(e){
        var file_list = e.target.files;
        console.log( e.target.files );
        $.each(file_list,function(idx,elm){
            
            $('.file_names').append('<input type="text" class="form-control margin_b_10" name="file_name[]" value="'+ elm.name.substr(0, elm.name.lastIndexOf('.')) +'">');
        });
    
    });
});
</script>
 {{--  <tr class="month_row">
    <th>Mjesec</th>
    @for ($i = 0; $i < $num_of_days_m; $i++)
        @php
            $first_month = $num_of_days_m - intval( $today_m->format('j'));
        @endphp
        <th class="align_center {!! $today_m->format('d') == 1 || $i== 0 ? 'month_first_day' : '' !!}" colspan="{!! $i == 0 ? (intval($first_month) + 1) : 1 !!}" >
            {!! $today_m->format('d') == 1 || $i == 0 ?  $today_m->format('m') : '' !!} 
        </th>
        @php
            $today_m->modify('+'. intval($first_month) + 1 .'days');
            $i += intval($first_month);
        @endphp
    @endfor
</tr> --}}
@stop
