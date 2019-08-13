@php
    use App\Models\Notice;

    $user = Sentinel::getUser()->employee;
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
        <a class="view_all" href="{{ route('noticeboard') }}" >@lang('basic.view_all')</a>
    </h2>
    @if(count($notices))
        @foreach ($notices as $notice)
            @php
                $notice_dep = explode(',', $notice->to_department);
            @endphp
            @if(array_intersect($user_department, $notice_dep) )
                <a href="{{ route('notices.show', $notice->id) }}" rel="modal:open">    
                    <article class="notice">
                        <div class="col-md-2 float_left">
                            <span class="notice_time">{{ date('H:i',strtotime($notice->created_at))}}<span>{{ date('l',strtotime($notice->created_at))}}</span></span>
                            <span class="notice_empl">
                                @php
                                    $docs = '';
                                    $user_name = explode('.',strstr($notice->employee['email'],'@',true));
                                    if(count($user_name) == 2) {
                                        $user_name = $user_name[1] . '_' . $user_name[0];
                                    } else {
                                        $user_name = $user_name[0];
                                    }

                                    $path = 'storage/' . $user_name . "/profile_img/";
                                    if(file_exists($path)){
                                        $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
                                    }else {
                                        $docs = '';
                                    }
                                @endphp
                                @if($docs)
                                <img class="notice_img radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image" title="{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}"  />
                                @else
                                    <img class="notice_img radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
                                @endif
                            </span>
                        </div>
                        <div class="col-md-10 float_left">
                            <span class="notice_text">{!! str_limit(strip_tags($notice->notice),300) !!}</span>
                        </div>
                    </article>
                </a>
            @endif
        @endforeach
    @endif
</section>
