@php
    use App\Models\Notice;
    use App\Models\Department;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\NoticeController;
	
    $permission_dep = DashboardController::getDepartmentPermission();
    $user_department = DashboardController::getUserDepartment();
  
    $notices = Notice::getNotice('DESC');
 
    $notices_user = collect();
   
    foreach ($notices as $notice) {
        $notice_dep = explode(',', $notice->to_department);

        if(array_intersect($user_department, $notice_dep) ) {
            $notices_user->push( $notice );
        }
    }
@endphp
<section class="col-12 float_left noticeboard">
    <h2>@lang('basic.noticeboard')
        @if(Sentinel::getUser()->employee && Sentinel::getUser()->hasAccess(['notices.create']) || in_array('notices.create', $permission_dep) )
            <a class="btn btn-primary btn-lg btn-new create_notice" href="{{ route('notices.create') }}" title="{{ __('basic.add_notice')}}">
                <i style="font-size:11px" class="fa" >&#xf067;</i>
            </a>
        @endif
        @if(Sentinel::getUser()->employee && count($notices_user)>0)
            <a class="view_all" href="{{ route('noticeboard') }}" >@lang('basic.view_all')</a>
        @endif
    </h2>
    <div>        
        <div class="notices_list">
            @if(count($notices_user)>0)
                @foreach ($notices_user->take(10) as $notice)
                    <a class="notice_show"  href="{{ route('notices.show', $notice->id) }}" rel="modal:open">
                        <article class="notice">
                            <div class="col-2 float_left">
                                <span class="notice_time">{{ date('d.m.',strtotime($notice->created_at))}}<span>{{ date('l H:i',strtotime($notice->created_at))}}</span></span>
                                <span class="notice_empl">
                                    @php
                                        $profile_image_notice = DashboardController::profile_image($notice->employee_id);
                                        $user_name_notice =  DashboardController::user_name($notice->employee_id);
                                    @endphp
                                    @if($profile_image_notice)
                                        <img class="notice_img" src="{{ URL::asset('storage/' . $user_name_notice . '/profile_img/' . end($profile_image_notice)) }}" alt="Profile image"  />
                                    @else
                                        <img class="notice_img radius50 " src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
                                    @endif
                                </span>
                            </div>
                            <div class="col-10 float_left">
                                <span class="notice_text">{!! $notice->title !!} </span>
                            </div>
                        </article>
                    </a>
                @endforeach
            @else 
                <div class="placeholder">
                    <img class="" src="{{ URL::asset('icons/placeholder_notice.png') }}" alt="Placeholder image" />
                    <p>@lang('basic.no_notice1')
                        <label type="text" class="add_new" rel="modal:open" >
                            <i style="font-size:11px" class="fa">&#xf067;</i>
                        </label>
                        @lang('basic.no_notice2')
                    </p>
                </div>
            @endif
        </div>
       
    </div>
</section>
<script>
    $(function(){
        var aside_height = $('.index_page.index_documents aside').height();
       
        $('.placeholder').show();        
    });
    
    $.getScript( '/../js/open_modal.js');
    
</script>