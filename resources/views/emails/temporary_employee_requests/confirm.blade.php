@component('mail::message')

@if($absence->absence['mark'] == "VIK")
   @lang('absence.req_received')
@else
   {{ __('absence.request') .' '. $absence->absence_type['name']  }}  @lang('basic.for')
    @if($absence->absence['mark'] != "IZL")
        {{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime($absence->end_date)) }} 
    @else
        {{ date("d.m.Y", strtotime($absence->start_date)) . ' od ' . $absence->start_time . ' do ' . $absence->end_time }}</h4>
    @endif
@endif
<br/> 
{{ $odobrenje }}
<br/> 
{{ $absence->approve_reason }}
<br/> 
{{ 'Odobrio: ' . $odobrio }}

<br>
{{ config('app.name') }}
@endcomponent