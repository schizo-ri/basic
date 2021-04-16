<!DOCTYPE html>
<html lang="hr" style="font-size: 12px;font-family: Arial, sans-serif;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Locco vožnje</title>
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		<style>
            body{
                overflow: hidden !important;
            }
			@page { margin:20px 20px 20px; size: landscape }
			@media print {
				.pageBreak {
					page-break-after: always;
                }
                .tr {
                    page-break-inside: avoid;
                }
            }
		</style>
        	<!-- Datatables -->
        <link rel="stylesheet" href="{{ URL::asset('/../css/all2.css') }}"/>
    </head>
    <body style="height:auto">
        <div class="export_loccos " >
            @foreach($cars as $key => $car)
            
                <div class="">
                    @if(count( $car->locco ) > 0)
                        @php   
                            $sum = 0;
                            $loccos = $car->locco->filter(function ($locco) use ($month) {
                                return  date('Y-m', strtotime($locco->date)) == $month;
                            });
                        @endphp
                        <div class="table-responsive pageBreak">
                            <div class="page-header">
                                <div class="index_table_filter">
                                    Locco vožnje za vozilo {{ $car->manufacturer . ' ' . $car->model . ' ' . $car->	registration }} - {{ date('Y-m', strtotime($month)) }}</h5>
                                </div>
                            </div>
                            <div id="index_table{{$car->id}}" class="display table table-hover sort_1_desc" style="size:A4 landscape;margin:0;" >
                                <div class="thead">
                                    <div class="tr">
                                        <div class="th sort_date" style="width: 10%;" >@lang('basic.date')</div>
                                        <div class="th" style="width: 10%">@lang('basic.car')</div>
                                        <div class="th" style="width: 10%">@lang('basic.employee')</div>
                                        <div class="th" style="width: 15%">@lang('basic.destination')</div>
                                        <div class="th" style="width: 5%">@lang('basic.start_km')</div>
                                        <div class="th" style="width: 5%">@lang('basic.end_km')</div>
                                        <div class="th" style="width: 5%">@lang('basic.distance')</div>
                                        <div class="th" style="width: 33.6%">@lang('basic.comment')</div>
                                    </div>
                                </div>
                                <div class="tbody">
                                    @foreach ($loccos as $locco)
                                        @php   
                                            $sum += $locco->distance;
                                        @endphp
                                        <div class="tr">
                                            <div class="td" style="width: 10%">{{ date('d.m.Y.', strtotime($locco->date)) }}</div>
                                            <div class="td" style="width: 10%">{{ $locco->car['registration'] }}</div>
                                            <div class="td" style="width: 10%">{!! $locco->employee ? $locco->employee->user['first_name'] . ' ' . $locco->employee->user['last_name'] : '' !!}</div>
                                            <div class="td" style="width: 15%">{{ $locco->destination }}</div>
                                            <div class="td" style="width: 5%">{{ $locco->start_km }}</div>
                                            <div class="td" style="width: 5%">{{ $locco->end_km }}</div>
                                            <div class="td" style="width: 5%">{{ $locco->distance }}</div>
                                            <div class="td" style="width: 33.6%">{{ $locco->comment }} </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="tfoot">
                                    <div class="tr">
                                        <div class="td" colspan="6" style="width: 55%">Ukupno prijeđenih kilometara</div>
                                        <div class="td" colspan="1" style="width: 5%">{{ $sum }}</div>
                                        <div class="td" colspan="1" style="width: 33.6%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(count( $car->fuel) > 0)
                        @php   
                            $sum = 0;
                            $fuels = $car->fuel->filter(function ($fuel) use ($month) {
                                return  date('Y-m', strtotime($fuel->date)) == $month;
                            });
                        @endphp
                        <div class="table-responsive pageBreak">
                            <div class="page-header">
                                <div class="index_table_filter">
                                Gorivo za vozilo {{ $car->manufacturer . ' ' . $car->model . ' ' . $car->	registration }} - {{ date('Y-m', strtotime($month)) }}</h5>
                                </div>
                            </div>
                            <div id="index_table_fuel{{$car->id}}" class="display table table-hover sort_1_desc" style="size:A4 landscape;margin:0;" >
                                <div class="thead">
                                    <div class="tr">
                                        <div class="th sort_date" style="width: 20%;" >@lang('basic.date')</div>
                                        <div class="th sort_date" style="width: 20%;" >@lang('basic.car')</div>
                                        <div class="th" style="width: 20%">@lang('basic.liters')</div>
                                        <div class="th fuel_th" style="width: 20%">@lang('basic.current_km')</div>
                                        <div class="th" style="width: 20%">@lang('basic.average_consumption') [l/100km]</div>
                                    </div>
                                </div>
                                <div class="tbody">
                                    @foreach ($fuels as $fuel)
                                        @php
                                            $fuel_prev = $car->fuel->where('car_id',$fuel->car_id)->where('date','<', $fuel->date)->first();
                                            $sum +=  $fuel->liters;
                                        @endphp
                                        <div class="tr">
                                            <div class="td" style="width: 20%">{{ date('d.m.Y.', strtotime($fuel->date)) }}</div>
                                            <div class="td" style="width: 20%">{{ $fuel->car['registration'] }}</div>
                                            <div class="td" style="width: 20%">{{ $fuel->liters}}</div>
                                            <div class="td fuel_td" style="width: 20%">{{ $fuel->km }}</div>
                                            <div class="td" style="width: 20%">{!! $fuel_prev ? round($fuel->liters / ($fuel->km - $fuel_prev->km)  * 100, 2) : 0 !!}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="tfoot">
                                    <div class="tr">
                                        <div class="td fuel_td" colspan="4" style="width: 80%;text-align:right">Ukupno točeno l</div>
                                        <div class="td" colspan="1" style="width: 20%;text-align:right">{{ $sum }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
               
            @endforeach
        </div>
        <script>
            $(function() {
        
            });
        </script>	
	</body>
</html>