@php
    use App\Models\Notice;
@endphp
<div id="schedule_modal">
    <form id="schedule_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('notices.update', Notice::orderBy('created_at','DESC')->first()->id) }}" enctype="multipart/form-data" >
        <div class="modal-header">
            <h3 class="panel-title">@lang('basic.schedule')</h3>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>@lang('basic.date')</label>
                <input name="schedule_date" type="date" class="form-control" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
                <input name="schedule" type="hidden" value="true">
            </div>
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
        </div>
    </form>
</div>