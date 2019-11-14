@component('mail::message')
# Introduction
@lang('emailing.new_notice_on_app')

@component('mail::button', ['url' => $url])
@lang('emailing.click_to_see')
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
