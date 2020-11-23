@extends('Centaur::layout')

@section('title', 'Projektiranje')
@php
  // $num_of_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
    $num_of_days = 31;
    $num_of_days_m = $num_of_days;
    $num_of_days_w = $num_of_days;
    /*  $today_m = new Datetime('now');*/
   $today_w = new Datetime('now'); 
   $today_d = new Datetime('now');
    
   setlocale(LC_TIME, 'HR.utf8');
@endphp
@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
        </div>
        <h1>Projektiranje</h1>
    </div>
    <div class="">
        <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 timeline">
            <div class="table-responsive">
                <span class="next_week"><i class="fas fa-chevron-right"></i></span>
                <span class="previous_week"><i class="fas fa-chevron-left"></i></span>
                <table class="table table-hover" id="">
                    <thead>
                        <tr class="week_row">
                            <th>Tjedan</th>
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
                            <th>Djelatnik</th>
                            @for ($i = 0; $i < $num_of_days; $i++)
                                @if($today_d->format('N')<=5)
                                    <th id="{{ $today_d->format('Y-m-d')}}" class="align_center {!! $today_d->format('N') == 1 || $i == 0 ? 'week_first_Day' : '' !!} {!! $today_d->format('N') == 7 || $i == $num_of_days ? 'week_last_Day' : '' !!}">{{ $today_d->format('d.m.') }} <br> {{ strftime('%a', strtotime($today_d->format('Y-m-d'))) }} 
                                    </th>
                                @endif
                                @php
                                    $today_d->modify('+1 day');
                                @endphp
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if (count($user->designins)>0)
                                @foreach ($user->designins as $key => $designing)
                                    @php
                                        $today1 = new Datetime('now');
                                        $count_days = count( $designing->list_date);
                                    @endphp
                                    <tr id="user_{{ $user->id }}">
                                        <td >{!! $key==0 ?  $user->first_name . ' ' . $user->last_name  : '' !!}</td>
                                        @for ($i = 0; $i <= $num_of_days; $i++)
                                            @if($today1->format('N')<=5)
                                                <td class="proj_user_color" 
                                                    id="{{ $user->id . '_' .$today1->format('Y-m-d') }}"
                                                    {!! $today1->format('N') == 1 || $i == 0  ? 'week_first_Day' : '' !!} {!! $today1->format('N') == 7 || $i == $num_of_days ? 'week_last_Day' : '' !!}
                                                    {!! in_array($today1->format('Y-m-d'), $designing->list_date ) ? 'style="background-color:'. $user->color.'"'  : '' !!} >
                                                    <span class="project_name">
                                                        {!! in_array($today1->format('Y-m-d'), $designing->list_date ) && $count_days == count( $designing->list_date) ? $designing->project_no . ' ' .  $designing->name : '' !!}
                                                    </span>
                                                </td>
                                            @endif
                                            @php
                                                if(in_array($today1->format('Y-m-d'), $designing->list_date )) {
                                                    $count_days--;
                                                }
                                                $today1->modify('+1 day');
                                            @endphp
                                        @endfor
                                    </tr>
                                @endforeach
                            @else
                                <tr id="user_{{ $user->id }}">
                                    <td >{{ $user->first_name . ' ' . $user->last_name }}</td>
                                    @php
                                        $today2 = new Datetime('now');
                                    @endphp
                                    @for ($i = 0; $i <= $num_of_days; $i++)
                                        @if($today2->format('N') <=5)
                                            <td class="proj_user_color" id="{{ $user->id . '_' .$today2->format('Y-m-d') }}" {!! $today2->format('N') == 1 || $i == 0  ? 'week_first_Day' : '' !!} {!! $today2->format('N') == 7 || $i == $num_of_days ? 'week_last_Day' : '' !!} >
                                                <span class="project_name"></span>
                                            </td>
                                        @endif
                                        @php
                                            $today2->modify('+1 day');
                                        @endphp
                                    @endfor
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        <span class="json_users" hidden> {{ json_encode($users )}}</span>
        </section>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class='btn-toolbar pull-right'>
                @if(Sentinel::getUser()->hasAccess(['designings.create']))
                    <a href="{{ route('designings.create') }}" rel="modal:open"><i class="fas fa-plus"></i> Dodaj novi projekt </a>
                @endif
            </div>
            <h1>Projekti</h1>
            <div class="table-responsive">
                <table class="table table-hover" id="index_table"
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
                                $employee = $designing->designer;
                            @endphp
                                <tr class="project_{{ $designing->id }} {!! $j>3 && $j>=$count ? 'align_top' : '' !!}">
                                    <td>{{ $designing->project_no }}</td>
                                    <td>{{ $designing->name  }}</td>
                                    <td>{{ date('d.m.Y', strtotime($designing->date) ) }}</td>
                                    <td>{{ $designing->manager->first_name . ' ' . $designing->manager->last_name  }}</td>
                                    <td>
                                        <form class="update_preparation_employee" accept-charset="UTF-8" role="form" method="post" action="{{ route('designings.update', $designing->id) }}" id="{{ $designing->id }}" >
                                            <fieldset>
                                                <div class="selectBox showCheckboxes" >
                                                    <label class="zaduzi_text {!! Sentinel::inRole('administrator') ? 'cursor' : '' !!}">
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
                                                @if(Sentinel::getUser()->hasAccess(['preparation_employees.create']))
                                                    <div class="checkboxes1" >
                                                       
                                                        @php
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
                                                        @endforeach
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
                                                <span class="file-upload" id="upload_{{ $designing->id }}"><i class="fas fa-upload"></i> Upload</span>
                                                <a href="{{ route('designings.edit', $designing->id) }}" class="btn " rel="modal:open">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                    Edit
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
    $('.timeline .next_week').click(function(){
        var users = jQuery.parseJSON($('.json_users').text());
        var user;
        var user_object;
        var user_list_date;
        var first_date;
        var user_designins;
        var date_on_list;
        var array_list;
        var background = 'inherit';
        var last_day = new Date($('.days_row th').last().attr('id'));
        var next_day; 
        var week = parseInt($('.week_row .week').last().text());
        week++;
        var this_row;
        var i;

        $('.week_row').append('<th class="align_center week" colspan="5">'+week+'</th>');
        
        for (i = 0; i < 7; i++) {
            next_day = new Date(last_day.setDate(last_day.getDate() + 1));
            if (next_day.getDay() > 0 && next_day.getDay() <= 5 ) {
                $('.days_row').append('<th id="'+next_day.getFullYear() + '-' + (next_day.getMonth()+1) + '-' + next_day.getDate() +'" class="align_center  ">'+next_day.getDate() + '.' + (next_day.getMonth()+1) + '.' +'<br> pet </th>');

                $( ".timeline tbody tr" ).each(function( index ) {
                    this_row = $(this);
                    user = $(this).attr('id');
                    user = user.replace("user_","");
                    user_object = $.grep(users, function(e){ return e.id == user; })[0];
                    user_designins = user_object.designins;
                    
                    $( this ).append('<td class="proj_user_color" id="'+ user +'_' + next_day.getFullYear() + '-' + (next_day.getMonth()+1) + '-' + next_day.getDate()+'"><span class="project_name"></span></td>');

                    if(user_designins.length > 0) {
                        $.each(user_designins, function( index, value ) {
                            
                            user_list_date = this.list_date;
                            first_date = user_list_date[0];
                            var array_list = $.map(user_list_date, function(value, index){
                                return [value];
                            });
                            if(array_list.includes(next_day.getFullYear() + '-' + (next_day.getMonth()+1) + '-' + next_day.getDate())) {
                                $('.proj_user_color#'+ user + '_' + next_day.getFullYear() + '-' + (next_day.getMonth()+1) + '-' + next_day.getDate() ).css("background-color", user_object.color);
                                $('.proj_user_color#'+ user + '_' + first_date + ' .project_name').text(this.project_no + ' ' + this.name);
                            }
                        });
                    }
                });
            }
        }
        var scroll = $('.days_row th').width() * 5;
        console.log(scroll);
        var leftPos = $('.table-responsive').scrollLeft();
        $(".table-responsive").animate({scrollLeft: leftPos + 500}, scroll);
    });


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
            /* $('#file_name').val(filename.substr(0, filename.lastIndexOf('.'))); */
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
