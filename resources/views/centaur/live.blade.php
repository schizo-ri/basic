<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>@yield('title')</title>

        <!-- Bootstrap - Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		
		<!--Jquery -->
        <script src="{{ URL::asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('/../node_modules/moment/moment.js') }}"></script>
        
       <!-- CSS modal -->
        <link rel="stylesheet" href="{{ URL::asset('node_modules/jquery-modal/jquery.modal.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ URL::asset('css/welcome_new.css') }}" type="text/css" />
 
        <link href="{{ URL::asset('node_modules/@fullcalendar/core/main.css') }}" rel='stylesheet' />
        <link href="{{ URL::asset('node_modules/@fullcalendar/daygrid/main.css') }}" rel='stylesheet' />
        <link href="{{ URL::asset('node_modules/@fullcalendar/list/main.css') }}" rel='stylesheet' />
    
        <script  src="{{ URL::asset('node_modules/@fullcalendar/core/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/daygrid/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/interaction/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/list/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/resource-common/main.js') }}"></script>
        @php
            $url = $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"] ;
            if(isset(parse_url($url)['query'])) {
                $date = str_replace("=","",strstr(parse_url($url)['query'],'='));
            } else {
                $date=date("Y-m-d");
            }

            ini_set('memory_limit','-1');

        @endphp
		@stack('stylesheet')
    </head>
    <body> 
    <div class="row calendar_main calendar_show">
        <aside class="col-12" >
            <div class="list">
                <div class="projects_list col-md-6 col-sm-12 first"> 
                    <h3>Raspored za dan {{ date('d.m.Y', strtotime($date)) }}</h3>
                    <div>
                        @foreach ($projects as $project)
                            @if($project_employees->where('project_id', $project->project_id)->where('date', $date)->first())
                                <div id="p_{{ $project->project_id}}" class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <h4>{{ $project->project_no . ' - ' . $project->name }} <!--     <a href="{{ action('ProjectEmployeeController@uskladi', $project->project_id) }}">uskladi</a>--></h4>
                                    @foreach ($categories as $category)
                                        @foreach ($project_employees->where('project_id',$project->project_id)->where('date', $date) as $project_employee)
                                            @if($category->id == $project_employee->employee['category_id'] )
                                                <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', $date)) > 1 ? 'double' : ' ' }}">
                                                    <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                    {{ $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                                    
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @foreach ($project_employees->where('project_id',$project->project_id)->where('date', $date) as $project_employee)
                                        @if($project_employee->employee && $project_employee->employee['category_id'] == 0)
                                            <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', $date)) > 1 ? 'double' : ' ' }}">
                                                <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                {{ $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="projects_list col-md-6 col-sm-12 second"> 
                    <h3>Raspored za dan {{ date_format(date_modify(date_create($date),"+1 days"),'d.m.Y') }}</h3>
                    <div>
                        @foreach ($projects as $project)
                            @if($project_employees->where('project_id', $project->project_id)->where('date', date_format(date_modify(date_create($date),"+1 days"),'Y-m-d'))->first())
                                <div id="p_{{ $project->project_id}}" class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <h4>{{ $project->project_no . ' - ' . $project->name }} <!--     <a href="{{ action('ProjectEmployeeController@uskladi', $project->project_id) }}">uskladi</a>--></h4>
                                    @foreach ($categories as $category)
                                        @foreach ($project_employees->where('project_id',$project->project_id)->where('date', date_format(date_modify(date_create($date),"+1 days"),'Y-m-d')) as $project_employee)
                                            @if($category->id == $project_employee->employee['category_id'] )
                                                <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', date_format(date_modify(date_create($date),"+1 days"),'Y-m-d'))) > 1 ? 'double' : ' ' }}">
                                                    <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                    {{ $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                                
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @foreach ($project_employees->where('project_id',$project->project_id)->where('date', date_format(date_modify(date_create($date),"+1 days"),'Y-m-d')) as $project_employee)
                                        @if($project_employee->employee['category_id'] == 0)
                                            <div class="{{  count($project_employees->where('employee_id', $project_employee->employee_id)->where('date', date_format(date_modify(date_create($date),"+1 days"),'Y-m-d'))) > 1 ? 'double' : ' ' }}">
                                                <p class="{{ $project_employee->date }}" title="{{ $project_employee->employee->category['description'] }}">
                                                {{ $project_employee->employee['first_name'] . ' ' . $project_employee->employee['last_name'] }}</p>
                                            
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
    </div>     
    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <!-- Bootstrap js -->
    <script src="{{ URL::asset('node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
    <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
    <script src="{{ asset('restfulizer.js') }}"></script>
    
    <!-- Jquery modal -->
    <script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.js') }}"></script>
    <!-- Modal js -->
    <script src="{{URL::asset('/../js/open_modal.js') }}"></script>
    <!-- Fullcalendar js 
    <script src="{{ URL::asset('/../js/fullCalendar1.js') }}"></script>	-->
    
    @stack('script')
</body>
</html>