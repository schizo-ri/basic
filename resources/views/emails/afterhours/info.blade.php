@component('mail::message')
# Prijavljeni prekovremeni sati

<p>Djelatnik, {{ $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name }} poslao je zahtjev za odobrenje izvršenog prekovremenog rada<br>
    za projekt: {{ $afterhour->project->erp_id . ' - ' . $afterhour->project->name }}<br>
    za {{ date("d.m.Y", strtotime( $afterhour->date)) . ' od ' . $afterhour->start_time  . ' do ' .  $afterhour->end_time }}</p>

<div><b>Napomena: </b></div>
<div class="marg_20">
    {{ $afterhour->comment }}
</div>		

<br>
{{ config('app.name') }}
@endcomponent
