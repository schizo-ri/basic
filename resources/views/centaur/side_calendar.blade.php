<div class="col-12 calendar_main">
    @if(Sentinel::getUser()->employee)
        <a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}"  rel="modal:open">
            <i style="font-size:11px" class="fa">&#xf067;</i>
        </a>
    @endif
    <div class="calender_view">
    </div>
</div>
<script>
    $(function() {
        $.getScript( '/../js/event.js');
    });
</script>
<link rel="stylesheet" href="{{ URL::asset('node_modules/pg-calendar/dist/css/pignose.calendar.css') }}" />
<script src="{{ URL::asset('node_modules/pg-calendar/dist/js/pignose.calendar.min.js') }}"></script>