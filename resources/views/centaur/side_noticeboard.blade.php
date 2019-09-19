@php
    use App\Models\Notice;
    use App\Models\Department;
    use App\Http\Controllers\DashboardController;

    $user = Sentinel::getUser()->employee;
    $departments = Department::get();
    $user_department = array();
    $permission_dep = array();

    if($user) {
        array_push($user_department, $user->work->department->id);

        array_push($user_department, $departments->where('level1',0)->first()->id);
        $permission_dep = explode(',', count($user->work->department->departmentRole) > 0 ? $user->work->department->departmentRole->toArray()[0]['permissions'] : '');
    }
    
    $notices = Notice::orderBy('created_at','DESC')->get();
@endphp
<section class="col-md-12 col-lg-4 float_left noticeboard">
    <h2>Notice Board 
        @if(Sentinel::getUser()->employee && Sentinel::getUser()->hasAccess(['notices.create']) || in_array('notices.create', $permission_dep) )
            <a class="btn btn-primary btn-lg btn-new create_notice" href="{{ route('notices.create') }}" rel="modal:open" >
                <i style="font-size:11px" class="fa">&#xf067;</i>
            </a>
        @endif
        @if(Sentinel::getUser()->employee)
            <a class="view_all" href="{{ route('noticeboard') }}" >@lang('basic.view_all')</a>
        @endif
    </h2>
    @if(count($notices))
        @foreach ($notices as $notice)
            @php
                $notice_dep = explode(',', $notice->to_department);
            @endphp
            @if(array_intersect($user_department, $notice_dep) )
                <a href="{{ route('notices.show', $notice->id) }}" rel="modal:open">    
                    <article class="notice">
                        <div class="col-1 float_left">
                            <span class="notice_time">{{ date('H:i',strtotime($notice->created_at))}}<span>{{ date('l',strtotime($notice->created_at))}}</span></span>
                            <span class="notice_empl">
                                @php
                                    $profile_image_notice = DashboardController::profile_image($notice->employee_id);
                                    $user_name_notice =  DashboardController::user_name($notice->employee_id);
                                @endphp
                                @if($profile_image_notice)
                                    <img class="notice_img radius50" src="{{ URL::asset('storage/' . $user_name_notice . '/profile_img/' . end($profile_image_notice)) }}" alt="Profile image"  />
                                @else
                                    <img class="notice_img radius50 " src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
                                @endif
                            </span>
                        </div>
                        <div class="col-11 float_left">
                            <span class="notice_text">{!! str_limit(strip_tags($notice->notice),300) !!}</span>
                        </div>
                    </article>
                </a>
            @endif
        @endforeach
    @endif
</section>
<script>
    $(function(){
        var aside_height = $('.index_page.index_documents aside').height();
        $('section.noticeboard').height(aside_height);

    });
   
</script>
