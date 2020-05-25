<?php
    $vars = Session::all();
    foreach ($vars as $key => $value) {
        switch($key) {
            case 'success':
            case 'error':
            case 'warning':
            case 'info':
            
?>
            <div class="row notification {!! session()->has('evaluation') ? 'modal_questionnaire' : ''!!}">
                <div class="modal-header">
                    @if(!  session()->has('evaluation') )
                        <span class="img-{{ $key }}"></span>
                    @endif
                </div>
                <div class="group_body_footer">
                    <div class="modal-body">
                        @if( session()->has('evaluation') )
                            <span class="img-{{ $key }}"></span>
                        @endif
                        <div class="alert alert-{{ ($key == 'error') ? 'danger' : $key }} alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>{{ ucfirst($key) }}:</strong> {!! $value !!}
                            @if(session()->has('absence'))
                                <p>@lang('ctrl.status_requests')<br>@lang('ctrl.all_requests_page'). </p>
                            @endif
                            @if(session()->has('evaluation'))
                                <p class="padd_t_15 margin_0">@lang('ctrl.q_thanks')</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if(session()->has('absence'))
                            <span>
                                <button class="btn_all" ><a href="{{ route('absences.index') }}" >@lang('absence.all_requests')</a></button>
                            </span>
                        @endif
                        <!--
                        @if(session()->has('evaluation'))
                            <span>
                                <button class="btn_all" ><a href="#" >Redo survay</a></button>
                            </span>
                        @endif-->
                        <button class="done"><a href="#close" rel="modal:close" >@lang('absence.done')</a></button>
                    </div>
                </div>
            </div>
<?php
                Session::forget($key);
                break;
             case 'data':
?>
             @if( session()->has('schedule') )
                <div class="row notification">
                    <div class="modal-header">
                        
                    </div>
                    <div class="group_body_footer">
                        <div class="modal-body">
                            <form id="schedule_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('notices.update', $value) }}" enctype="multipart/form-data" >
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
                        <div class="modal-footer">
                          
                        </div>
                    </div>
                </div>
            @endif
<?php
                Session::forget($key);
                break;
            default:
        }
    }
?>
 <script>
     $(function(){          
        $('.row.notification').modal();
     });
</script>