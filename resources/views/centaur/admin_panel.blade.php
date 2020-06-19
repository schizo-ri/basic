@extends('Centaur::layout')

@section('title', __('welcome.admin_panel'))

@section('content')
     @if(Sentinel::check() && Sentinel::inRole('administrator'))
        <div class="index_page index_admin">
            <aside class="col-md-12 col-lg-4 col-xl-4 float_left admin_aside">
                <section class="col-12 float_left admin_panel">
                    <h1>@lang('welcome.admin_panel')</h1>
                    <ul class="admin_pages">
                        @if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin'))
                            <li class="first_group {{ Request::is('users*') ? 'active' : '' }}">
                                <span class="image_users" ></span> 
                                <a href="{{ route('users.index') }}" class="line_height_61 admin_link" id="click_users" >@lang('basic.users')</a>
                            </li>
                            <li class="first_group {{ Request::is('roles*') ? 'active' : '' }}"><span class="space" ></span> 
                                <a href="{{ route('roles.index') }}" class="line_height_45 admin_link" id="roles"> @lang('basic.roles')</a>
                            </li>
                            <li class="first_group {{ Request::is('employees*') ? 'active' : '' }}"><span class="space" ></span>
                                <a href="{{ route('employees.index') }}" class="line_height_45 admin_link" id="employees">@lang('basic.employees')</a>
                            </li>
                            <li class="first_group {{ Request::is('departments*') ? 'active' : '' }}"><span class="space" ></span> 
                                <a href="{{ route('departments.index') }}" class="line_height_45 admin_link" id="departments">@lang('basic.departments')</a>
                            </li>
                            <li class="first_group {{ Request::is('departments*') ? 'active' : '' }}"><span class="space" ></span> 
                                <a href="{{ route('department_roles.index') }}" class="line_height_45 admin_link" id="department_roles">@lang('basic.department_roles')</a>
                            </li>
                            <li class="first_group {{ Request::is('works*') ? 'active' : '' }}"><span class="space" ></span>
                                <a href="{{ route('works.index') }}"  class="line_height_45 admin_link" id="works">@lang('basic.works')</a>
                            </li>
                            @if(in_array('Oglasnik',$moduli))
                                <li class="first_group {{ Request::is('ad_categories*') ? 'active' : '' }}"><span class="space" ></span>
                                    <a href="{{ route('ad_categories.index') }}"  class="line_height_45 admin_link" id="ad_categories">@lang('basic.ad_categories')</a>
                                </li>
                            @endif
                            <li class="first_group {{ Request::is('absence_types*') ? 'active' : '' }}"><span class="space" ></span>
                                <a href="{{ route('absence_types.index') }}"  class="line_height_45 admin_link" id="absence_types">@lang('absence.abs_types')</a>
                            </li>              
                            @if(in_array('Locco vožnja', $moduli))
                                <li class="first_group {{ Request::is('cars*') ? 'active' : '' }}">
                                    <span class="space" ></span>
                                    <a href="{{ route('cars.index') }}" class="line_height_45 admin_link" id="cars">@lang('basic.cars')</a>
                                </li>
                                <li class="first_group {{ Request::is('loccos*') ? 'active' : '' }}">
                                    <span class="space" ></span>
                                    <a href="{{ route('loccos.index') }}" class="line_height_45 admin_link" id="cars">@lang('basic.loccos')</a>
                                </li>
                                <li class="first_group {{ Request::is('travel_orders*') ? 'active' : '' }}">
                                    <span class="space" ></span>
                                    <a href="{{ route('travel_orders.index') }}" class="line_height_45 admin_link" id="cars">@lang('basic.travel_orders')</a>
                                </li>
                            @endif
                            <li class="{{ Request::is('companies*') ? 'active' : '' }}">
                                <span class="image_company" ></span> 
                                <a href="{{ route('companies.index') }}" class="line_height_61 admin_link" id="companies">@lang('basic.company')</a>
                            </li>
                            <li class="{{ Request::is('emailings*') ? 'active' : '' }}">
                                <span class="image_emailings" ></span> 
                                <a href="{{ route('emailings.index') }}" class="line_height_61 admin_link" id="emailings">@lang('basic.emailings')</a>
                            </li>      
                            <li class="{{ Request::is('templates*') ? 'active' : '' }}">
                                <span class="image_template" ><i class="far fa-clone"></i></span> 
                                <a href="{{ route('templates.index') }}" class="line_height_61 admin_link" id="emailings">@lang('basic.templates')</a>
                            </li>   
                        @endif
                        @if (Sentinel::inRole('superadmin') )
                            <li class="{{ Request::is('settings*') ? 'active' : '' }}">
                                <span class="image_settings" ><i class="fas fa-cog"></i></span> 
                                <a href="{{ route('settings.index') }}" class="line_height_61 admin_link" id="settings">@lang('basic.settings')</a>
                            </li>
                            <li class="{{ Request::is('tables*') ? 'active' : '' }}">
                                <span class="image_tables" ></span> 
                                <a href="{{ route('tables.index') }}" class="line_height_61 admin_link" id="tables">@lang('basic.tables')</a>
                            </li>
                        @endif
                    </ul>
                </section>
            </aside>
            <main class="col-md-12 col-lg-8 col-xl-8 index_main float_right admin_main">
                <section>
                    <span class="link_back"><span class="curve_arrow_left"></span></span>
                    <div id="admin_page">
                    </div>
                </section>
            </main>
        </div>
    @endif    
@stop