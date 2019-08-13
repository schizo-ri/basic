<div class="modal-header">
    <a class="link_back" rel="modal:close">
        <img src="{{ URL::asset('icons/arrow_left2.png') }}" />
    </a>
    @if(Sentinel::getUser()->hasAccess(['notices.update']) || in_array('notices.update', $permission_dep) )
        <a class="view_all" href="{{ route('noticeboard') }}" >
            <img class="img_statistic" src="{{ URL::asset('icons/curve-arrow_right.png') }}" alt="all notice" />
            <span>Jump to all notices</span>
        </a>
        <a href="#" class="btn-statistic">
            <img class="img_statistic" src="{{ URL::asset('icons/arrow_statistic.png') }}" alt="statistic" />
            <span>Statistic</span>
        </a>
        <a href="{{ route('notices.edit', $notice->id) }}" class="btn-edit" rel="modal:open" >
            <img class="img_statistic" src="{{ URL::asset('icons/edit.png') }}" alt="edit" />
            <span>Edit</span>
        </a>
    @endif
    <section class="statistic">
    <div class="col-3 float_l"><p class="info_statistic">{{ number_format($statistic, 0) . '%' }}</p><span class="info_statistic">READ THIS NOTICE</span></div>
        <div class="col-3 float_l"><p class="info_statistic">{{ number_format($statistic, 0) . '%' }}</p><span class="info_statistic">READ WHOLE NOTICE</span></div>
        <div class="col-3 float_l"><p class="info_statistic">{{ number_format($statistic, 0) . '%' }}</p><span class="info_statistic">READ AT LEAST 50%</span></div>
        <div class="col-3 float_l"><p class="info_statistic"><img src="{{ URL::asset('icons/horiz_dots.png') }}" /></p><span class="info_statistic">DETAILED STATS</span></div>
    </section>
    <h3 class="panel-title">{{ $notice->title }}</h3>
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
    <span class="notice_name">
        @if($docs)
        <img class="notice_img radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image" title="{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}"  />
        @else
        <img class="notice_img radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
        @endif
        {{ $notice->employee->user['first_name'] . ' ' .  $notice->employee->user['last_name']}}
    </span>
    <p class="notice_date">{{  date('l, d.F Y.', strtotime($notice->created_at)) }}</p>
</div>
<div class="modal-body">
    {!! $notice->notice !!}
</div>
<script>
    $(function() {
        $('.modal').addClass('modal_notice');
        $('.modal').addClass('notice_show');
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal-body').height(body_height);
       
    });
    $( window ).resize(function() {
        $('.modal').addClass('modal_notice');
        $('.modal').addClass('notice_show');
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal-body').height(body_height);
       
    });
    $('.btn-statistic').click(function(){
        $('.statistic').toggle();
    });
    

</script>