<ul class="admin_pages">
    @if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin'))
        <li class="first_group">
            <span class="image_users" ></span> 
            <a href="" class="line_height_45 admin_link open_menu" id="hr_links" >LJUDSKI POTENCIJALI <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
        </li>
            <li class="first_group hr_links ">
                <span class="space" ></span> 
                <a href="{{ route('users.index') }}" class="line_height_61 admin_link {{ Request::is('users*') ? 'active_admin' : '' }}" id="users" >@lang('basic.users')</a>
            </li>
            <li class="first_group hr_links"><span class="space" ></span>
                <a href="{{ route('employees.index') }}" class="line_height_45 admin_link {{ Request::is('employees*') ? 'active_admin' : '' }}" id="employees">@lang('basic.employees')</a>
            </li>
            <li class="first_group hr_links"><span class="space" ></span> 
                <a href="{{ route('employee_departments.index') }}" class="line_height_45 admin_link {{ Request::is('employee_departments*') ? 'active_admin' : '' }}" id="employee_departments">@lang('basic.employee_departments')</a>
            </li>
            @if(in_array('Privremeni', $moduli))  
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('temporary_employees.index') }}" class="line_height_45 admin_link {{ Request::is('temporary_employees*') ? 'active_admin' : '' }}" id="temporary_employees">@lang('basic.temporary_employees')</a>
                </li>
            @endif
            <li class="first_group hr_links"><span class="space" ></span>
                <a href="{{ route('kids.index') }}" class="line_height_45 admin_link {{ Request::is('kids*') ? 'active_admin' : '' }}" id="kids">@lang('basic.kids')</a>
            </li>
            @if(in_array('Razgovori', $moduli))  
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('job_interviews.index') }}" class="line_height_45 admin_link {{ Request::is('job_interviews*') ? 'active_admin' : '' }}" id="job_interviews">@lang('basic.job_interviews')</a>
                </li>
            @endif
            @if(in_array('Osposobljavanja', $moduli))
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('employee_trainings.index') }}" class="line_height_45 admin_link {{ Request::is('employee_trainings*') ? 'active_admin' : '' }}" id="employee_trainings">@lang('basic.employee_trainings')</a>
                </li>
            @endif
            <li class="first_group hr_links"><span class="space" ></span>
                <a href="{{ route('employee_terminations.index') }}" class="line_height_45 admin_link {{ Request::is('employee_terminations*') ? 'active_admin' : '' }}" id="employee_terminations">@lang('basic.employee_terminations')</a>
            </li>
            <li class="first_group hr_links"><span class="space" ></span>
                <a href="{{ route('day_offs.index') }}" class="line_height_45 admin_link {{ Request::is('day_offs*') ? 'active_admin' : '' }}" id="day_offs">@lang('basic.days_off')</a>
            </li>
        <li class="first_group">
            <span class="space" ></span> 
            <a href="" class="line_height_45 admin_link open_menu" id="abs_links" >EVIDENCIJA<span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
        </li>
            @if(in_array('Evidencija', $moduli))
                <li class="first_group abs_links"><span class="space" ></span>
                    <a href="{{ route('work_records.index') }}" class="line_height_45 admin_link {{ Request::is('work_records*') ? 'active_admin' : '' }}" id="work_records">@lang('basic.work_records')</a>
                </li>
            @endif
            @if(in_array('Prekovremeni', $moduli))  
                <li class="first_group abs_links"><span class="space" ></span>
                    <a href="{{ route('afterhours.index') }}" class="line_height_45 admin_link {{ Request::is('afterhours*') ? 'active_admin' : '' }}" id="afterhours">@lang('basic.afterhours')</a>
                </li>
            @endif
            @if(in_array('Privremeni', $moduli))  
                <li class="first_group abs_links"><span class="space" ></span>
                    <a href="{{ route('temporary_employee_requests.index') }}" class="line_height_45 admin_link {{ Request::is('temporary_employee_requests*') ? 'active_admin' : '' }}" id="temporary_employee_requests">@lang('basic.temporary_employee_requests')</a>
                </li>
            @endif
        <li class="first_group">
            <span class="space" ></span> 
            <a href="" class="line_height_45 admin_link open_menu" id="project_links" >PROJEKTI <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
        </li>
            <li class="first_group project_links"><span class="space" ></span> 
                <a href="{{ route('projects.index') }}" class="line_height_45 admin_link {{ Request::is('projects*') ? 'active_admin' : '' }}" id="projects">@lang('basic.projects')</a>
            </li>
            <li class="first_group project_links"><span class="space" ></span> 
                <a href="{{ route('customers.index') }}" class="line_height_45 admin_link {{ Request::is('customers*') ? 'active_admin' : '' }}" id="customers">@lang('basic.customers')</a>
            </li>
        <li class="first_group">
            <span class="space" ></span> 
            <a href="" class="line_height_45 admin_link open_menu" id="car_links" >LOCCO VOŽNJE <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
        </li>  
            @if(in_array('Locco vožnja', $moduli))
                <li class="first_group car_links">
                    <span class="space " ></span>
                    <a href="{{ route('cars.index') }}" class="line_height_45 admin_link {{ Request::is('cars*') ? 'active_admin' : '' }}" id="cars">@lang('basic.cars')</a>
                </li>
                <li class="first_group car_links">
                    <span class="space" ></span>
                    <a href="{{ route('loccos.index') }}" class="line_height_45 admin_link {{ Request::is('loccos*') ? 'active_admin' : '' }} " id="loccos">@lang('basic.loccos')</a>
                </li>
                <li class="first_group car_links">
                    <span class="space" ></span>
                    <a href="{{ route('fuels.index') }}" class="line_height_45 admin_link {{ Request::is('fuels*') ? 'active_admin' : '' }}" id="fuels">@lang('basic.fuel')</a>
                </li>
                <li class="first_group car_links">
                    <span class="space" ></span>
                    <a href="{{ route('vehical_services.index') }}" class="line_height_45 admin_link {{ Request::is('vehical_services*') ? 'active_admin' : '' }}" id="vehical_services">@lang('basic.vehical_services')</a>
                </li>
                @if(in_array('Putni nalozi', $moduli))
                    <li class="first_group car_links">
                        <span class="space" ></span>
                        <a href="{{ route('travel_orders.index') }}" class="line_height_45 admin_link {{ Request::is('travel_orders*') ? 'active_admin' : '' }}" id="travel_orders">@lang('basic.travel_orders')</a>
                    </li>
                @endif
            @endif
        <li class="first_group">
            <span class="space" ></span> 
            <a href="" class="line_height_45 admin_link open_menu" id="basic_links" >OPĆI PODACI  <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
        </li>
            <li class="first_group basic_links"><span class="space" ></span> 
                <a href="{{ route('roles.index') }}" class="line_height_45 admin_link {{ Request::is('roles*') ? 'active_admin' : '' }}" id="roles"> @lang('basic.roles')</a>
            </li>
            <li class="first_group basic_links"><span class="space" ></span> 
                <a href="{{ route('departments.index') }}" class="line_height_45 admin_link {{ Request::is('departments*') ? 'active_admin' : '' }}" id="departments">@lang('basic.departments')</a>
            </li>
            <li class="first_group basic_links"><span class="space" ></span> 
                <a href="{{ route('department_roles.index') }}" class="line_height_45 admin_link {{ Request::is('department_roles*') ? 'active_admin' : '' }}" id="department_roles">@lang('basic.department_roles')</a>
            </li>
            <li class="first_group basic_links"><span class="space" ></span>
                <a href="{{ route('works.index') }}"  class="line_height_45 admin_link {{ Request::is('works*') ? 'active_admin' : '' }}" id="works">@lang('basic.works')</a>
            </li>
            <li class="first_group basic_links"><span class="space" ></span>
                <a href="{{ route('instructions.index') }}" class="line_height_45 admin_link {{ Request::is('instructions*') ? 'active_admin' : '' }}" id="works">@lang('basic.instructions')</a>
            </li>
            @if(in_array('Osposobljavanja', $moduli))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('trainings.index') }}" class="line_height_45 admin_link {{ Request::is('trainings*') ? 'active_admin' : '' }}" id="employees">@lang('basic.trainings')</a>
                </li>
            @endif
            <li class="first_group basic_links"><span class="space" ></span>
                <a href="{{ route('absence_types.index') }}"  class="line_height_45 admin_link {{ Request::is('absence_types*') ? 'active_admin' : '' }}" id="absence_types">@lang('absence.abs_types')</a>
            </li>
            @if(in_array('Oglasnik',$moduli))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('ad_categories.index') }}"  class="line_height_45 admin_link {{ Request::is('ad_categories*') ? 'active_admin' : '' }}" id="ad_categories">@lang('basic.ad_categories')</a>
                </li>
            @endif
            <li class="first_group basic_links"><span class="space" ></span>
                <a href="{{ route('terminations.index') }}"  class="line_height_45 admin_link {{ Request::is('terminations*') ? 'active_admin' : '' }}" id="terminations">@lang('basic.termination_types')</a>
            </li>
        <li class="">
            <span class="image_company" ></span> 
            <a href="{{ route('companies.index') }}" class="line_height_61 admin_link {{ Request::is('companies*') ? 'active_admin' : '' }}" id="companies">@lang('basic.company')</a>
        </li>
        <li class="">
            <span class="image_emailings" ></span> 
            <a href="{{ route('emailings.index') }}" class="line_height_61 admin_link {{ Request::is('emailings*') ? 'active_admin' : '' }}" id="emailings">@lang('basic.emailings')</a>
        </li>
        <li class="">
            <span class="space" ></span>
            <a href="{{ route('mail_templates.index') }}" class="line_height_45 admin_link {{ Request::is('mail_templates*') ? 'active_admin' : '' }}" id="mail_templates">@lang('basic.mail_templates')</a>
        </li>      
        <li class="">
            <span class="image_template" ><i class="far fa-clone"></i></span> 
            <a href="{{ route('templates.index') }}" class="line_height_61 admin_link {{ Request::is('templates*') ? 'active_admin' : '' }}" id="templates">@lang('basic.templates')</a>
        </li>   
    @endif
    @if (Sentinel::inRole('superadmin') )
        <li class="">
            <span class="image_settings" ><i class="fas fa-cog"></i></span> 
            <a href="{{ route('settings.index') }}" class="line_height_61 admin_link {{ Request::is('settings*') ? 'active_admin' : '' }}" id="settings">@lang('basic.settings')</a>
        </li>
        <li class="">
            <span class="image_tables" ></span> 
            <a href="{{ route('tables.index') }}" class="line_height_61 admin_link {{ Request::is('tables*') ? 'active_admin' : '' }}" id="tables">@lang('basic.tables')</a>
        </li>
    @endif
</ul>