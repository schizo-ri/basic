@component('mail::message')
# {{ $employee->user->first_name }}, do sada si poslao {{ $brojAnketa }} od minimalno 15 anketa. Preostalo za ocijeniti minimalno {{ 15 - $brojAnketa }} anketa.

<br>
{{ config('app.name') }}
@endcomponent
