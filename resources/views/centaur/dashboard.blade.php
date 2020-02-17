@extends('Centaur::layout')

@section('title', 'Raspored')
@php
    $url = $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"] ;
    if(isset(parse_url($url)['query'])) {
        $date = str_replace("=","",strstr(parse_url($url)['query'],'='));
    } else {
        $date = date("Y-m-d");
    }
    $tommorow = date('Y-m-d', strtotime('+1 day', strtotime($date)));
@endphp

@section('content')

<div class="row calendar_main">
    @if (Sentinel::check())    
        <main class="col-lg-6 col-md-12" >
            <div id='calendar'></div>
            <div>
                <h2 class="toCanvas" style="display: none">To Canvas</h2>
                <h2 class="toPic" style="display: none">To Image</h2>
                <label for="imgW" style="display: none">Image Width:</label>
         
            </div>   
            <div hidden class="dataArr">{!! json_encode($dataArr) !!}</div>
            <div hidden class="dataArrResource">{!! json_encode($dataArrResource) !!}</div>
        </main>    
        <aside class="col-lg-6 col-md-12">
            <span class="publish_btn pull_right" >Publish</span>
            <form id="fupForm" enctype="multipart/form-data">

            </form>
            <script>
                var test = $("#calendar").get(0);

                $( "span.publish_btn" ).click(function( event ) {
                   
                    html2canvas(test).then(function(canvas) {
                        var canvasWidth = canvas.width;// canvas width
                        var canvasHeight = canvas.height;  // canvas height
                     //   $('.toCanvas').after(canvas);// render canvas
                        var img = Canvas2Image.convertToImage(canvas, canvasWidth, canvasHeight);
                     //   $(".toPic").after(img); // render image
                        var d = new Date();

                        let f = 'Raspored_' + d.getFullYear() + (d.getMonth() +1) + d.getDate() + d.getHours() + d.getMinutes();// file name
                        // save as image
                        Canvas2Image.saveAsImage(canvas, canvasWidth, canvasHeight, 'png', f);

                        var dataURL = canvas.toDataURL(); 
                        var imgdata = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
                        $.ajax({
                            type: 'POST',
                            url: 'saveImg',
                            data: {'imgCanvas':imgdata,
                                    '_token':  $('meta[name="csrf-token"]').attr('content') },
                            beforeSend: function(){
                                // Show image container
                                $("#loader").show();
                            },
                            success: function(data){
                                
                              alert('Podaci su objavljeni!');
                            },
                            complete:function(data){
                                // Hide image container
                                $("#loader").hide();
                            }
                        });
                       
                    });
                });
            </script>
            <div id='external-events' class="clear_r">
                <div class="resource">
                    <p>
                        <a href="{{ route('employees.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" title="Dodaj novog djelatnika" /></a>
                    </p>
                    @foreach ($categories as $category)
                        <div class="col-20">
                            <h4 title="{{  $category->description }}">{{  $category->mark . ' | ' }} <small> {{ $category->description }}</small></h4>
                            @foreach ($employees->where('category_id', $category->id ) as $employee)
                                @if(! $project_employees->where('employee_id', $employee->id )->where('date', $date)->first())
                                    <div class='fc-event' id="{{$employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }} </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach   
                    @if($employees && count($employees->where('category_id', 0 )))
                        <div class="clear col-20">
                            <h4>{{  'BEZ KATEGORIJE' }}</h4>
                            @foreach ($employees->where('category_id', 0 ) as $employee)
                                @if(! $project_employees->where('employee_id', $employee->id )->where('date', $date)->first())
                                    <div class='fc-event' id="{{$employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }} </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class='btn-toolbar'>
                <a href="{{ route('projects.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" title="Dodaj novi projekt" /></a>
            </div>
            <div class="list">
                <div class="projects_list first">
                    <h3>Raspored za dan {{ date('d.m.Y', strtotime($date)) }}</h3>
                    <div>
                        @foreach ($projects as $project)
                            @if($project_employees->where('project_id', $project->id)->where('date', $date)->first())
                                <div id="p_{{ $project->id}}" class="col-20">
                                    <h4>{{ $project->project_no . ' - ' . $project->name }} <!--     <a href="{{ action('ProjectEmployeeController@uskladi', $project->id) }}">uskladi</a>--></h4>
                                    @foreach ($categories as $category)
                                        @foreach ($project_employees->where('project_id',$project->id)->where('date', $date) as $project_employee)
                                            @if($category->id == $project_employee->employee['category_id'] )
                                                <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', $date)) > 1 ? 'double' : ' ' }}">
                                                    <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                    {{ $project_employee->employee->category['mark'] . ' | ' .  $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                                    <!-- <a href="{{ route('project_employees.destroy', $project_employee->id) }}" class="delete_btn action_confirm" data-method="delete" data-token="{{ csrf_token() }}" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>-->
                                                    <form class="delete_form" action="{{ action('ProjectEmployeeController@destroy', ['id' => $project_employee->id]) }}" method="POST" onSubmit="if(!confirm('Želiš li stvarno obrisati djelatnika sa projekta?')){return false;}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="" title="Obriši djelatnika sa projekta">
                                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @foreach ($project_employees->where('project_id',$project->id)->where('date', $date) as $project_employee)
                                        @if($project_employee->employee['category_id'] == 0)
                                            <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', $date)) > 1 ? 'double' : ' ' }}">
                                                <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                {{ $project_employee->employee->category['mark'] . ' | ' .  $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                                <!-- <a href="{{ route('project_employees.destroy', $project_employee->id) }}" class="delete_btn action_confirm" data-method="delete" data-token="{{ csrf_token() }}" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>-->
                                                <form class="delete_form" action="{{ action('ProjectEmployeeController@destroy', ['id' => $project_employee->id]) }}" method="POST" onSubmit="if(!confirm('Želiš li stvarno obrisati djelatnika sa projekta?')){return false;}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="" title="Obriši djelatnika sa projekta">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="projects_list second">
                    <h3>Raspored za dan {{ date('d.m.Y', strtotime($tommorow)) }}</h3>
                    <div>
                        @foreach ($projects as $project)
                            @if($project_employees->where('project_id', $project->id)->where('date', $tommorow)->first())
                                <div id="p_{{ $project->id}}" class="col-20">
                                    <h4>{{ $project->project_no . ' - ' . $project->name }} <!--     <a href="{{ action('ProjectEmployeeController@uskladi', $project->id) }}">uskladi</a>--></h4>
                                    @foreach ($categories as $category)
                                        @foreach ($project_employees->where('project_id',$project->id)->where('date', $tommorow) as $project_employee)
                                            @if($category->id == $project_employee->employee['category_id'] )
                                                <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', $tommorow)) > 1 ? 'double' : ' ' }}">
                                                    <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                    {{ $project_employee->employee->category['mark'] . ' | ' .  $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                                    <!-- <a href="{{ route('project_employees.destroy', $project_employee->id) }}" class="delete_btn action_confirm" data-method="delete" data-token="{{ csrf_token() }}" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>-->
                                                    <form class="delete_form" action="{{ action('ProjectEmployeeController@destroy', ['id' => $project_employee->id]) }}" method="POST" onSubmit="if(!confirm('Želiš li stvarno obrisati djelatnika sa projekta?')){return false;}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="" title="Obriši djelatnika sa projekta">
                                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @foreach ($project_employees->where('project_id',$project->id)->where('date', $tommorow) as $project_employee)
                                        @if($project_employee->employee['category_id'] == 0)
                                            <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', $tommorow)) > 1 ? 'double' : ' ' }}">
                                                <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                {{ $project_employee->employee->category['mark'] . ' | ' .  $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                                <!-- <a href="{{ route('project_employees.destroy', $project_employee->id) }}" class="delete_btn action_confirm" data-method="delete" data-token="{{ csrf_token() }}" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>-->
                                                <form class="delete_form" action="{{ action('ProjectEmployeeController@destroy', ['id' => $project_employee->id]) }}" method="POST" onSubmit="if(!confirm('Želiš li stvarno obrisati djelatnika sa projekta?')){return false;}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="" title="Obriši djelatnika sa projekta">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>
        
      
    @else
        Nisi prijavljen
    @endif
</div>
<!-- Image loader -->
<div id='loader' style='display: none;'>
    <img src='{{ URL::asset('icons/ajax-loader1.gif') }}' width='100px' height='100px'>
</div>
 <!-- Fullcalendar js -->
 <script src="{{ URL::asset('/../js/fullCalendar.js') }}"></script>
		
<script>
function show_alert() {
  if(!confirm("")) {
    return false;
  }
  this.form.submit();
}
$('.fc-next-button').click(function(){
    $.getScript( "/../js/open_modal.js");
    console.log("fc-next-button");
});
$(function(){
    $('a.fc-day-grid-event').attr('rel','modal:open');
});
</script>
@stop