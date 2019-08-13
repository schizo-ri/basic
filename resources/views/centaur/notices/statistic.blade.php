<div class="modal-header">
    <a class="link_back" rel="modal:close">
        <img src="{{ URL::asset('icons/arrow_left2.png') }}" />
    </a>
    @if(Sentinel::getUser()->hasAccess(['notices.update']) || in_array('notices.update', $permission_dep) )
        <a href="{{ route('notices.edit', $notice->id) }}" class="btn-edit">
            <i class="far fa-edit"></i> Edit
        </a>
    
        <a href="{{ route('notices.statistic', $notice->id) }}" class="btn-statistic">
            <i class="far fa-edit"></i>Statistic
        </a>
    @endif
    <a class="view_all" href="{{ route('notices.index') }}" >Jump to all notices</a>
    <h3 class="panel-title">{{ $notice->title }}</h3>
</div>
<div class="modal-body">
    {!! $notice->notice !!}
</div>
<script>
    $(function() {
        $('.modal').addClass('modal_notice');
        $('.modal').addClass('notice_show');
    });
</script>