
<div class="modal-header">
    <a class="link_back" rel="modal:close">
        <img src="{{ URL::asset('icons/arrow_left2.png') }}" />
    </a>
    @if(Sentinel::getUser()->hasAccess(['notices.update']) || in_array('notices.update', $permission_dep) )
        <a class="view_all" href="{{ route('noticeboard') }}" >
            <img class="img_statistic" src="{{ URL::asset('icons/curve-arrow_right.png') }}" alt="all notice" />
            <span>@lang('basic.jump_all_notice')</span>
        </a>
        <a href="#" class="btn-statistic">
            <img class="img_statistic" src="{{ URL::asset('icons/arrow_statistic.png') }}" alt="statistic" />
            <span>@lang('basic.statistic')</span>
        </a>
        <a href="{{ route('notices.edit', $notice->id) }}" class="btn-edit" >
            <img class="img_statistic" src="{{ URL::asset('icons/edit.png') }}" alt="edit" />
            <span>@lang('basic.edit')</span>
        </a>
    @endif
    <section class="statistic">
        <div class="col-3 float_l"><p class="info_statistic">{{ number_format($statistic, 0) . '%' }}</p><span class="info_statistic">READ THIS NOTICE</span></div>
        <div class="col-3 float_l"><p class="info_statistic">{{ number_format($statistic, 0) . '%' }}</p><span class="info_statistic">READ WHOLE NOTICE</span></div>
        <div class="col-3 float_l"><p class="info_statistic">{{ number_format($statistic, 0) . '%' }}</p><span class="info_statistic">READ AT LEAST 50%</span></div>
        <div class="col-3 float_l"><a href="{{ route('notice_statistics.index', ['notice_id' => $notice->id] ) }}" class="open_statistic" rel="modal:open" ><p class="info_statistic"><img src="{{ URL::asset('icons/horiz_dots.png') }}" /></p><span class="info_statistic">DETAILED STATS</span></a></div>
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
        $notice_img = '';
        $path_notice = 'storage/notice/' . $notice->id . '/';
        if(file_exists($path_notice)){
            $notice_img = array_diff(scandir($path_notice), array('..', '.', '.gitignore'));
        } else {
            $notice_img = '';
        }
    @endphp
    <span class="notice_name">
        @if($docs)
        <img class="notice_img radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image" title="{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}"  />
        @else
        <img class="notice_img radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
        @endif
        {{ $notice->employee->user['first_name'] . ' ' .  $notice->employee->user['last_name']}}
    </span>
    <p class="notice_date">{{  date('l, d.F Y.', strtotime($notice->created_at)) }}</p>
</div>
<div class="modal-body">
    @if($notice_img)
    @php krsort($notice_img); @endphp
        <div class="image_notice">
            <img class="img_notice" src="{{ URL::asset('storage/notice/' . $notice->id . '/' . end($notice_img)) }}" alt="Notice image" title="Zoom"  />
        </div>
    @endif
    <div class="notice_content" >
        {!! $notice->notice !!}
    </div>
</div>
<script>
    $(function() {     
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal-body').height(body_height);
       
    });
    $( window ).resize(function() {
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 25;
        $('.modal-body').height(body_height);
       
    });
    $('.btn-statistic').click(function(){
        $('.statistic').toggle();
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 25;
        $('.modal-body').height(body_height);
    });

    $('.image_notice').click(function(){
        if($(this).hasClass('show_all')) {
            $(this).removeClass('show_all');
            $(this).find('.img_notice').removeClass('show_all');
        } else {
            $(this).addClass('show_all');
            $(this).find('.img_notice').addClass('show_all');
        }
    });
    
    $('.open_statistic').click(function(){
        $.getScript('/../node_modules/chart.js/dist/Chart.js');
    });
    $.getScript( '/../js/open_modal.js'); 
    /* $("a[rel='modal:close']").click(function(){
        $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: false,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
      };    
    }); */
</script>