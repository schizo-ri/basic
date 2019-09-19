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
                <input name="date" type="datetime-local" class="form-control" value="{{ old('date') }}" required>
                <input name="schedule" type="hidden" value="true">
            </div>
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
        </div>
    </form>
</div>
<script>
  //  $('form#schedule_form').submit(function(event){
    //    event.preventDefault();
       // $('form#notice_form').submit();
       // $('form#schedule_form').submit();
   //    $('form').submit();
     
  //  });
  
    $( 'form#schedule_form').one( "submit", function() {
        event.preventDefault();
        $('form').each(function(){
             $( this ).submit();
        });
       
    });
</script>