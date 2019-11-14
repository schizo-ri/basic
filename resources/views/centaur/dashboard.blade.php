@extends('Centaur::layout')

@section('title', 'Raspored')
@php
    $url = $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"] ;
    if(isset(parse_url($url)['query'])) {
        $date = str_replace("=","",strstr(parse_url($url)['query'],'='));
    } else {
        $date=date("Y-m-d");
    }
@endphp
@section('content')
<div class="row">
    @if (Sentinel::check())
        <main class="" style="float:left; width:75%;padding-left: 30px;">
            <div id='calendar'></div>
            <div hidden class="dataArr">{!! json_encode($dataArr) !!}</div>
        </main>    
        <aside class="" style="float:left; width:25%;padding-left: 30px;">
            <div id='external-events'>
                <div class="resource">
                <p>
                    <a href="{{ route('employees.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
                </p>
                @foreach ($employees as $employee)
                    @if(! $project_employees->where('employee_id', $employee->id )->where('date', $date)->first())
                        <div class='fc-event' id="{{$employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }} </div>
                    @endif
                @endforeach
            <!-- <p>
                    <input type='checkbox' id='drop-remove' />
                    <label for='drop-remove'>remove after drop</label>
                </p>-->
    
                </div>
            </div>
            <div class='btn-toolbar'>
                <a href="{{ route('projects.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
               
            </div>
            <div class="projects_list">
                @foreach ($projects as $project)
                    <div>
                        <h4>{{ $project->project_no . ' - ' . $project->name }} 
                        <a href="{{ action('ProjectEmployeeController@brisi',$project->id) }}">uskladi</a>
                        </h4>
                        @foreach ($project_employees->where('project_id',$project->id)->where('date', $date) as $project_employee)
                            <div>
                                <p class="{{ $project_employee->date }}">
                                    {{ $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                    <!-- <a href="{{ route('project_employees.destroy', $project_employee->id) }}" class="delete_btn action_confirm" data-method="delete" data-token="{{ csrf_token() }}" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>-->
                                    <form class="delete_form" action="{{ action('ProjectEmployeeController@destroy', ['id' => $project_employee->id]) }}" method="POST" onSubmit="if(!confirm('Želiš li stvarno obrisati djelatnika na označeni dan?')){return false;}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="" title="Obriši djelatnika sa projekta">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </button>
                                    </form>
                                    
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>         
        </aside>
    @else
        Nisi prijavljen
    @endif
</div>
<script>
function show_alert() {
  if(!confirm("")) {
    return false;
  }
  this.form.submit();
}

$(function(){
    $('a.fc-day-grid-event').attr('rel','modal:open');
});
</script>
@stop