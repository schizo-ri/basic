<!DOCTYPE html>
<html lang="hr" style="font-size: 12px;font-family: Arial, sans-serif;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Evidencija</title>
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		<style>
			@page { margin:20px; size: landscape }
			@media print {
				.pageBreak {
					page-break-after: always;
				}
                .export_loccos table  {
                    max-width: 2480px;
                    width:100%;
                    height:95%
                }
                .export_loccos table td{
                    width: auto;
                    overflow: hidden;
                    word-wrap: break-word;
                }
            }
		</style>
        	<!-- Datatables -->
        <link rel="stylesheet" href="{{ URL::asset('/../css/all1.css') }}"/>
    </head>
    <body>
        <div class="export_loccos " >
            @foreach($cars as $key => $car)
                @if($key == 0 && count( $car->locco) > 0)
                    @php   
                        $sum = 0;
                    @endphp
                    <div class="table-responsive pageBreak">
                        <div class="page-header">
                            <div class="index_table_filter">
                                Locco vožnje za vozilo {{ $car->manufacturer . ' ' . $car->model . ' ' . $car->	registration }} - {{ date('Y-m', strtotime($month)) }}</h5>
                            </div>
                        </div>
                        <table id="index_table{{$car->id}}" class="display table table-hover sort_1_desc" style="size:A4 landscape;margin:0;">
                            <thead>
                                <tr>
                                    <th class="sort_date" style="width: 10%;" >@lang('basic.date')</th>
                                    <th style="width: 10%">@lang('basic.car')</th>
                                    <th style="width: 10%">@lang('basic.employee')</th>
                                    <th style="width: 15%">@lang('basic.destination')</th>
                                    <th style="width: 5%">@lang('basic.start_km')</th>
                                    <th style="width: 5%">@lang('basic.end_km')</th>
                                    <th style="width: 5%">@lang('basic.distance')</th>
                                    <th style="width: 30%">@lang('basic.comment')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($car->locco as $locco)
                                    @php   
                                        $sum += $locco->distance;
                                    @endphp
                                    <tr style="width: 100%">
                                        <td style="width: 10%">{{ date('d.m.Y.', strtotime($locco->date)) }}</td>
                                        <td style="width: 10%">{{ $locco->car['registration'] }}</td>
                                        <td style="width: 10%">{!! $locco->employee ? $locco->employee->user['first_name'] . ' ' . $locco->employee->user['last_name'] : '' !!}</td>
                                        <td style="width: 15%">{{ $locco->destination }}</td>
                                        <td style="width: 5%">{{ $locco->start_km }}</td>
                                        <td style="width: 5%">{{ $locco->end_km }}</td>
                                        <td style="width: 5%">{{ $locco->distance }}</td>
                                        <td style="width: 30%">{{ $locco->comment }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" style="width: 55%">Ukupno prijeđenih kilometara</td>
                                    <td colspan="1" style="width: 5%">{{ $sum }}</td>
                                    <td colspan="1" style="width:30%"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            @endforeach
        </div>
        <script>
            $(function() {
        
            });
        </script>	
	</body>
</html>