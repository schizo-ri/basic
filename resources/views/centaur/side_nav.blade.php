@php
    use App\Models\Questionnaire;
    use App\Models\Campaign;
	$countQuestionnaire =  Questionnaire::countQuestionnaire();
    $countCampaign = Campaign::countCampaign();
@endphp
<section class="section_top_nav" id="section_top_nav">
    <span class="close_topnav">
        @if(file_exists('../public/storage/company_img/logo.png'))
            <img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo"/>
        @else 
            <img src="{{ URL::asset('icons/myIntranet.png')}}" alt="company_logo"/>
        @endif
        <i class=" fas fa-times"></i></span>
    <div class="topnav" id="myTopnav">
        <div class="">
            <a class="button_nav dashboard_button active" href="{{ route('dashboard') }}" title="{{ __('welcome.dashboard') }}">
                <span class="button_nav_img arrow_dashboard"></span>
                <p class="button_nav_text">@lang('welcome.home')</p>
            </a>
        </div>
        @if(in_array('Poruke', $moduli))
            @if(Sentinel::getUser()->hasAccess(['posts.view']) || in_array('posts.view', $permission_dep) )
                <div class="div_posts">
                    <a class="button_nav load_button posts_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('posts.index') }}" title="{{ __('basic.posts') }}">
                        <span class="button_nav_img messages"><!-- <img class="" src="{{ URL::asset('../icons/messages_grey.png') }}" alt="Profile image"  /> -->
                            <span class="line_btn">
                                @if($countComment_all >0)<span class="count_comment">{{ $countComment_all }}</span>@endif  
                            </span>
                        </span>
                        <p class="button_nav_text">@lang('basic.posts')</p>
                    </a>
                </div>
            @endif
        @endif
        @if(in_array('Dokumenti', $moduli))
            @if(Sentinel::getUser()->hasAccess(['documents.view']) || in_array('documents.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button documents_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('documents.index') }}" title="{{ __('basic.documents') }}">
                        <span class="button_nav_img documents "><!-- <img class="" src="{{ URL::asset('../icons/documents_grey.png') }}" alt="Profile image"  /> --></span>
                        <p class="button_nav_text">@lang('basic.documents')</p>
                    </a>
                </div>
            @endif
        @endif
        @if(in_array('Kalendar', $moduli))
            @if(Sentinel::getUser()->hasAccess(['events.view']) || in_array('events.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button events_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('events.index') }}" title="{{ __('calendar.events') }}" >
                        <span class="button_nav_img calendar"><!-- <img class="" src="{{ URL::asset('../icons/calendar_grey.png') }}" alt="Profile image"  /> --></span>
                        <p class="button_nav_text">@lang('calendar.calendar')</p>
                    </a>
                </div>
            @endif
        @endif
        <!--Provjera kod superadmina ima li korisnik modul-->
        @if(in_array('Ankete', $moduli) && $countQuestionnaire > 0)
            @if(Sentinel::getUser()->hasAccess(['questionnaires.view']) || in_array('questionnaires.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button questionnaires_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('questionnaires.index') }}"  title="{{ __('questionnaire.questionnaires') }}">
                        <span class="button_nav_img questionnaire"><!-- <img class="" src="{{ URL::asset('../icons/list_grey.png') }}" alt="Profile image"  /> --></span>
                        <p class="button_nav_text">@lang('questionnaire.questionnaires')</p>	
                    </a>
                </div>
            @endif
        @endif
        @if(in_array('Oglasnik',$moduli))
            @if(Sentinel::getUser()->hasAccess(['ads.view']) || in_array('ads.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button oglasnik_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('oglasnik') }}" title="{{ __('basic.ads') }}">
                        <span class="button_nav_img ads"><!-- <img class="" src="{{ URL::asset('../icons/ads_grey.png') }}" alt="Profile image"  /> --></span>
                        <p class="button_nav_text">@lang('basic.ads')</p>	
                    </a>	
                </div>
            @endif
        @endif
        @if(in_array('Kampanje', $moduli) && $countCampaign > 0 && ! Sentinel::inRole('administrator'))
            @if(Sentinel::getUser()->hasAccess(['campaigns.view']) || in_array('campaigns.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button campaigns_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('campaigns.index') }}" title="{{ __('basic.campaigns') }}">
                        <span class="button_nav_img campaign"><!-- <img class="" src="{{ URL::asset('../icons/messages_grey.png') }}" alt="Profile image"  /> --></span>
                        <p class="button_nav_text">@lang('basic.campaigns')</p>	
                    </a>	
                </div>
            @endif
        @endif
        @if(in_array('Pogodnosti', $moduli))							
            @if(Sentinel::getUser()->hasAccess(['benefits.view']) || in_array('benefits.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button benefits_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('benefits.index') }}" title="{{ __('basic.benefits') }}">
                        <span class="button_nav_img benefits"><!-- <img class="" src="{{ URL::asset('../icons/messages_grey.png') }}" alt="Profile image"  /> --></span>
                        <p class="button_nav_text">@lang('basic.benefits')</p>
                    </a>	
                </div>
            @endif
        @endif
      {{--   @if(in_array('Radne upute', $moduli)) --}}
            @if(Sentinel::getUser()->hasAccess(['instructions.view']) || in_array('instructions.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button contacts_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('contacts') }}" title="{{ __('basic.contacts') }}">
                        <span class="button_nav_img "><i class="far fa-address-book"></i></span>
                        <p class="button_nav_text">@lang('basic.contacts')</p>
                    </a>	
                </div>
            @endif
       {{--  @endif --}}
       @if(Sentinel::getUser()->hasAccess(['instructions.view']) || in_array('instructions.view', $permission_dep) )
        <div class="">
            <a class="button_nav load_button instructions_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('radne_upute') }}" title="{{ __('basic.instructions') }}">
                <span class="button_nav_img "><i class="fas fa-book-open"></i></span>
                <p class="button_nav_text">@lang('basic.instructions')</p>
            </a>	
        </div>
    @endif
        @if(in_array('Edukacije', $moduli))
            @if(Sentinel::getUser()->hasAccess(['educations.view']) || in_array('educations.view', $permission_dep) )
                <div class="">
                    <a class="button_nav load_button educations_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('educations.index') }}" title="{{ __('basic.educations') }}">
                        <span class="button_nav_img "><i class="fas fa-user-graduate"></i></span>
                        <p class="button_nav_text">@lang('basic.educations')</p>
                    </a>	
                </div>
            @endif
        @endif
    </div>
</section>