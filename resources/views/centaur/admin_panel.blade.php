@extends('Centaur::layout')

@section('title', __('welcome.admin_panel'))

@section('content')
     @if(Sentinel::check() && Sentinel::inRole('administrator'))
        <div class="index_page index_admin">
            <aside class="col-lg-12 col-xl-12 float_left admin_aside">
                <section class="col-xs-12 col-sm-4 float_left admin_panel">
                    <h1>@lang('welcome.admin_panel')</h1>
                    <ul class="admin_pages">
                        @if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin'))
                            <li class="first_group {{ Request::is('users*') ? 'active' : '' }}">
                                <span class="image_users" ></span> 
                                <a href="{{ route('users.index') }}" class="line_height_61" id="click_users" >@lang('basic.users')</a>
                            </li>
                            <li class="first_group {{ Request::is('roles*') ? 'active' : '' }}"><span class="space" ></span> 
                                <a href="{{ route('roles.index') }}" class="line_height_45"> @lang('basic.roles')</a>
                            </li>
                            <li class="first_group {{ Request::is('employees*') ? 'active' : '' }}"><span class="space" ></span>
                                <a href="{{ route('employees.index') }}" class="line_height_45" >@lang('basic.employees')</a>
                            </li>
                            <li class="first_group {{ Request::is('departments*') ? 'active' : '' }}"><span class="space" ></span> 
                                <a href="{{ route('departments.index') }}" class="line_height_45">@lang('basic.departments')</a>
                            </li>
                            <li class="first_group {{ Request::is('departments*') ? 'active' : '' }}"><span class="space" ></span> 
                                <a href="{{ route('department_roles.index') }}" class="load_page line_height_45">@lang('basic.department_roles')</a>
                            </li>
                            <li class="first_group {{ Request::is('works*') ? 'active' : '' }}"><span class="space" ></span>
                                <a href="{{ route('works.index') }}"  class="line_height_45">@lang('basic.works')</a>
                            </li>
                            <li class="first_group {{ Request::is('ad_categories*') ? 'active' : '' }}"><span class="space" ></span>
                                <a href="{{ route('ad_categories.index') }}"  class="line_height_45" >@lang('basic.ad_categories')</a>
                            </li>
                            <li class="first_group {{ Request::is('absence_types*') ? 'active' : '' }}"><span class="space" ></span>
                                <a href="{{ route('absence_types.index') }}"  class="line_height_45" >@lang('absence.abs_types')</a>
                            </li>
                            <li class="{{ Request::is('companies*') ? 'active' : '' }}">
                                <span class="image_company" ></span> 
                                <a href="{{ route('companies.index') }}" class="line_height_61">@lang('basic.company')</a>
                            </li>
                            <li class="{{ Request::is('emailings*') ? 'active' : '' }}">
                                <span class="image_emailings" ></span> 
                                <a href="{{ route('emailings.index') }}" class="line_height_61">@lang('basic.emailings')</a>
                            </li>
                        @endif
                        @if (Sentinel::inRole('superadmin'))
                            <li class="{{ Request::is('tables*') ? 'active' : '' }}">
                                <span class="image_tables" ></span> 
                                <a href="{{ route('tables.index') }}" class="line_height_61">@lang('basic.tables')</a>
                            </li>
                        @endif
                    </ul>
                </section>
            </aside>
            <main class="col-lg-12 col-xl-8 index_main float_right admin_main">
                <span class="link_back"><span class="curve_arrow_left"></span></span>
                <div id="admin_page">
                </div>
            </main>
    @endif
@stop