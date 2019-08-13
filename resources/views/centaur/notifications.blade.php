<?php
    $vars = Session::all();
    foreach ($vars as $key => $value) {
        switch($key) {
            case 'success':
            case 'error':
            case 'warning':
            case 'info':
                ?>
                @if(session()->has('modal')) 
                    <div class="row" id="modal_notification">
                            <div class="modal-header">
                                <span class="img-{{ $key }}"></span>
                            </div>
                        <div class="modal-body">
                            <div class="alert alert-{{ ($key == 'error') ? 'danger' : $key }} alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>{{ ucfirst($key) }}:</strong> {!! $value !!}
                                @if(session()->has('absence'))<p class="padd_t_15 margin_0">To see you request status and see all request visit <br>all requests page. </p>@endif
                            </div>
                        </div>
                        @if(session()->has('modal'))
                            <div class="modal-footer">
                                <span>
                                    @if(session()->has('absence')) <button class="btn_all" ><a href="{{ route('absences.index') }}" >@lang('absence.all_requests')</a></button>@endif
                                    <button class="done"><a href="#close" rel="modal:close" >@lang('absence.done')</a></button>
                                </span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="row notification">
                        <div class="alert alert-{{ ($key == 'error') ? 'danger' : $key }} alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>{{ ucfirst($key) }}:</strong> {!! $value !!}
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
