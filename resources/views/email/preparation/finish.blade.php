@component('mail::message')
#  Ormar {{ $preparation->name }} po projektu {{ $preparation->project_no }} {!! $preparation->project_name ? ' - ' . $preparation->project_name : '' !!}
# {{ $text }}

Za više informacija kontaktirati 

voditelja: {{ $preparation->manager->first_name . ' ' . $preparation->manager->last_name }}

projektanta: {{ $preparation->designed->first_name . ' ' . $preparation->designed->last_name }}
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent