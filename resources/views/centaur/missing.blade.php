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
        
       <!-- CSS modal -->
        <link rel="stylesheet" href="{{ URL::asset('node_modules/jquery-modal/jquery.modal.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ URL::asset('css/welcome_new.css') }}" type="text/css" />
 
    
        <script  src="{{ URL::asset('node_modules/@fullcalendar/core/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/daygrid/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/interaction/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/list/main.js') }}"></script>
        <script type="module" src="{{ URL::asset('node_modules/@fullcalendar/resource-common/main.js') }}"></script>
        
        @php
            use App\Http\Controllers\PreparationController;
            ini_set('memory_limit','-1');
          
        @endphp
		@stack('stylesheet')
    </head>
    <body> 
    <div class="row missing_main">
        <main class="col-md-12" >
            <div class="list">
                <div class="missing_list col-md-12"> 
                    <h1>Nedostaci projekata</h1>
                    <span id="time"></span>
                    <div>
                        @if(count($preparations) > 0)
                            @foreach ($preparations as $preparation)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 div_preparations " >
                                    <div>
                                        <h3>{{ $preparation->project_no . ' ' .  $preparation->name }}</h3>
                                        <h4>Rok isporuke <span class="{{ date('Y-m-d',strtotime($preparation->delivery)) }}">{{ date('d.m.Y',strtotime($preparation->delivery)) }}</span> </h4>
                                        <p>Zaduženi u pripremi:
                                            @foreach ($preparation->employees as $zaduzen)
                                                <span>{{ $zaduzen->user->first_name . ' ' . $zaduzen->user->last_name }}, </span>
                                            @endforeach
                                        </p>
                                        @foreach ($preparation->equipment as $item)
                                            @if ($item->updates->sum('quantity') < $item->quantity )
                                                <p class="{!! $item->updates->sum('quantity') != 0 && $item->quantity > $item->updates->sum('quantity')? 'partial' : '' !!} {!! $item->updates->sum('quantity') == 0 ? 'not_delivered' : '' !!}">{{ $item->product_number . ' ' . str_limit($item->name, 50) .'quantity: ' . $item->quantity . ' / delivered: '. $item->updates->sum('quantity') }} </p>
                                            @endif
                                        @endforeach
                                        
                                    </div>

                                </div>
                            @endforeach
                        @endif
                       
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
    <script>
    $(function() {
        var today = new Date();
        var prep_date;
        $( ".div_preparations>div>h4>span" ).each(function(index, value) {
            prep_date = new Date($(this).attr('class'));
            var diff = prep_date - today;
            diff_days = Math.round(diff/1000/60/60/24);
            if(diff_days<=5 ) {
                $( this ).parent().parent().addClass( "red_border_prep" );
            }
        });

      });
        var decimalTimeString  = 900;
        setInterval( function() {
            decimalTimeString--;
            var n = new Date(0,0);
            n.setSeconds(+decimalTimeString * 60 );
            $('#time').html("Slijedeće osvježavanje stranice za " + n.toTimeString().slice(0, 5) + " minuta");
            if (decimalTimeString === 0) {
                location.reload();
            }    
        }, 1000 );

    </script>
    @stack('script')
</body>
</html>