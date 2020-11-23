@component('mail::message')
# {{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }} poslao je poruku na projekt {{ $comment->designing->project_no . ' - ' . $comment->designing->name }} 

Poruka glasi: 

{{ $comment->comment }}

@component('mail::button', ['url' => $link])
Odgovori ovdje
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
