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
                                To see you request status and see all request visit <br>all requests page. </p>
                            @endif
                            @if(session()->has('evaluation'))
                                <p class="padd_t_15 margin_0">Thank you for participating in this survey, considered finishing another one if available.</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if(session()->has('absence'))
                            <span>
                                <button class="btn_all" ><a href="{{ route('absences.index') }}" >@lang('absence.all_requests')</a></button>
                            </span>
                        @endif
                        @if(session()->has('evaluation'))
                            <span>
                                <button class="btn_all" ><a href="#" >Redo survay</a></button>
                            </span>
                        @endif
                        <button class="done"><a href="#close" rel="modal:close" >@lang('absence.done')</a></button>
                    </div>
                </div>
            </div>
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