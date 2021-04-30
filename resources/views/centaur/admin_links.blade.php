<ul class="admin_pages">
    @if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin') || Sentinel::inRole('moderator')|| Sentinel::inRole('racunovodstvo'))
        @if(Sentinel::getUser()->hasAccess(["users.view"]) || Sentinel::getUser()->hasAccess(["employees.view"])  )
            <li class="first_group">
                <span class="image_users" ></span> 
                <a href="" class="line_height_45 admin_link open_menu" id="hr_links" >LJUDSKI POTENCIJALI <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
            </li>
        @endif
            @if(Sentinel::getUser()->hasAccess(["users.view"]))
                <li class="first_group hr_links ">
                    <span class="space" ></span> 
                    <a href="{{ route('users.index') }}" class="line_height_61 admin_link {{ Request::is('users*') ? 'active_admin' : '' }}" id="users" >@lang('basic.users')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["employees.view"]))
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('employees.index') }}" class="line_height_45 admin_link {{ Request::is('employees*') ? 'active_admin' : '' }}" id="employees">@lang('basic.employees')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["employee_departments.view"]))
                <li class="first_group hr_links"><span class="space" ></span> 
                    <a href="{{ route('employee_departments.index') }}" class="line_height_45 admin_link {{ Request::is('employee_departments*') ? 'active_admin' : '' }}" id="employee_departments">@lang('basic.employee_departments')</a>
                </li>
            @endif
            @if(in_array('Privremeni', $moduli) && Sentinel::getUser()->hasAccess(["temporary_employees.view"]) )
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('temporary_employees.index') }}" class="line_height_45 admin_link {{ Request::is('temporary_employees*') ? 'active_admin' : '' }}" id="temporary_employees">@lang('basic.temporary_employees')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["kids.view"]))
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('kids.index') }}" class="line_height_45 admin_link {{ Request::is('kids*') ? 'active_admin' : '' }}" id="kids">@lang('basic.kids')</a>
                </li>
            @endif
            @if(in_array('Razgovori', $moduli) && Sentinel::getUser()->hasAccess(["job_interviews.view"]))
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('job_interviews.index') }}" class="line_height_45 admin_link {{ Request::is('job_interviews*') ? 'active_admin' : '' }}" id="job_interviews">@lang('basic.job_interviews')</a>
                </li>
            @endif
            @if(in_array('Osposobljavanja', $moduli) && Sentinel::getUser()->hasAccess(["employee_trainings.view"]))
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('employee_trainings.index') }}" class="line_height_45 admin_link {{ Request::is('employee_trainings*') ? 'active_admin' : '' }}" id="employee_trainings">@lang('basic.employee_trainings')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["employee_terminations.view"]))
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('employee_terminations.index') }}" class="line_height_45 admin_link {{ Request::is('employee_terminations*') ? 'active_admin' : '' }}" id="employee_terminations">@lang('basic.employee_terminations')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["day_offs.view"]))
                <li class="first_group hr_links"><span class="space" ></span>
                    <a href="{{ route('day_offs.index') }}" class="line_height_45 admin_link {{ Request::is('day_offs*') ? 'active_admin' : '' }}" id="day_offs">@lang('basic.days_off')</a>
                </li>
            @endif
        @if(in_array('Evidencija', $moduli) ||  in_array('Prekovremeni', $moduli) || in_array('Dnevnik', $moduli) || in_array('Privremeni', $moduli))
            @if(Sentinel::getUser()->hasAccess(["vacations.create"]) || Sentinel::getUser()->hasAccess(["afterhours.view"]) )
                <li class="first_group">
                    <span class="space" ></span> 
                    <a href="" class="line_height_45 admin_link open_menu" id="abs_links" >EVIDENCIJA<span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
                </li>
            @endif     
                @if(Sentinel::getUser()->hasAccess(["vacations.create"]) )
                    <li class="first_group abs_links"><span class="space" ></span>
                        <a href="{{ route('vacations.index') }}" class="line_height_45 admin_link {{ Request::is('vacations*') ? 'active_admin' : '' }}" id="vacations">@lang('absence.vacations')</a>
                    </li>
                @endif
                @if(in_array('Evidencija', $moduli) && Sentinel::getUser()->hasAccess(["work_records.view"]))
                    <li class="first_group abs_links"><span class="space" ></span>
                        <a href="{{ route('work_records.index') }}" class="line_height_45 admin_link {{ Request::is('work_records*') ? 'active_admin' : '' }}" id="work_records">@lang('basic.work_records')</a>
                    </li>
                @endif
                @if(in_array('Prekovremeni', $moduli) && Sentinel::getUser()->hasAccess(["afterhours.view"]))
                    <li class="first_group abs_links"><span class="space" ></span>
                        <a href="{{ route('afterhours.index') }}" class="line_height_45 admin_link {{ Request::is('afterhours*') ? 'active_admin' : '' }}" id="afterhours">@lang('basic.afterhours')</a>
                    </li>
                @endif
                @if(in_array('Dnevnik', $moduli) && Sentinel::getUser()->hasAccess(["work_diaries.view"]))
                    <li class="first_group abs_links"><span class="space" ></span>
                        <a href="{{ route('work_diaries.index') }}" class="line_height_45 admin_link {{ Request::is('work_diaries*') ? 'active_admin' : '' }}" id="work_diaries">@lang('basic.work_diary')</a>
                    </li>
                @endif
                @if(in_array('Privremeni', $moduli) && Sentinel::getUser()->hasAccess(["temporary_employee_requests.view"]))
                    <li class="first_group abs_links"><span class="space" ></span>
                        <a href="{{ route('temporary_employee_requests.index') }}" class="line_height_45 admin_link {{ Request::is('temporary_employee_requests*') ? 'active_admin' : '' }}" id="temporary_employee_requests">@lang('basic.temporary_employee_requests')</a>
                    </li>
                @endif
        @endif
        @if(in_array('Projekti', $moduli) && Sentinel::getUser()->hasAccess(["projects.view"]))
            <li class="first_group">
                <span class="space" ></span> 
                <a href="" class="line_height_45 admin_link open_menu" id="project_links" >@lang('basic.projects') <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
            </li>
                @if(Sentinel::getUser()->hasAccess(["projects.view"]))
                    <li class="first_group project_links"><span class="space" ></span> 
                        <a href="{{ route('projects.index') }}" class="line_height_45 admin_link {{ Request::is('projects*') ? 'active_admin' : '' }}" id="projects">@lang('basic.projects')</a>
                    </li>
                @endif
                @if(Sentinel::getUser()->hasAccess(["contracts.view"]))
                    <li class="first_group project_links"><span class="space" ></span> 
                        <a href="{{ route('contracts.index') }}" class="line_height_45 admin_link {{ Request::is('contracts*') ? 'active_admin' : '' }}" id="contracts">@lang('basic.contracts')</a>
                    </li>
                @endif
                @if(Sentinel::getUser()->hasAccess(["customers.view"]))
                    <li class="first_group project_links"><span class="space" ></span> 
                        <a href="{{ route('customers.index') }}" class="line_height_45 admin_link {{ Request::is('customers*') ? 'active_admin' : '' }}" id="customers">@lang('basic.customers')</a>
                    </li>
                @endif
        @endif
        @if(Sentinel::getUser()->hasAccess(["cars.view"]) || Sentinel::getUser()->hasAccess(["loccos.view"]))
            <li class="first_group">
                <span class="space" ></span> 
                <a href="" class="line_height_45 admin_link open_menu" id="car_links" >LOCCO VOŽNJE <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
            </li>  
        @endif
            @if(in_array('Locco vožnja', $moduli))
                @if(Sentinel::getUser()->hasAccess(["cars.view"]))
                    <li class="first_group car_links">
                        <span class="space " ></span>
                        <a href="{{ route('cars.index') }}" class="line_height_45 admin_link {{ Request::is('cars*') ? 'active_admin' : '' }}" id="cars">@lang('basic.cars')</a>
                    </li>
                @endif
                @if(Sentinel::getUser()->hasAccess(["loccos.view"]))
                    <li class="first_group car_links">
                        <span class="space" ></span>
                        <a href="{{ route('loccos.index') }}" class="line_height_45 admin_link {{ Request::is('loccos*') ? 'active_admin' : '' }} " id="loccos">@lang('basic.loccos')</a>
                    </li>
                @endif
                @if(Sentinel::getUser()->hasAccess(["fuels.view"]))
                    <li class="first_group car_links">
                        <span class="space" ></span>
                        <a href="{{ route('fuels.index') }}" class="line_height_45 admin_link {{ Request::is('fuels*') ? 'active_admin' : '' }}" id="fuels">@lang('basic.fuel')</a>
                    </li>
                @endif
                @if(Sentinel::getUser()->hasAccess(["vehical_services.view"]))
                    <li class="first_group car_links">
                        <span class="space" ></span>
                        <a href="{{ route('vehical_services.index') }}" class="line_height_45 admin_link {{ Request::is('vehical_services*') ? 'active_admin' : '' }}" id="vehical_services">@lang('basic.vehical_services')</a>
                    </li>
                @endif
                @if(in_array('Putni nalozi', $moduli) && Sentinel::getUser()->hasAccess(["travel_orders.view"]))
                    <li class="first_group car_links">
                        <span class="space" ></span>
                        <a href="{{ route('travel_orders.index') }}" class="line_height_45 admin_link {{ Request::is('travel_orders*') ? 'active_admin' : '' }}" id="travel_orders">@lang('basic.travel_orders')</a>
                    </li>
                @endif
            @endif
        @if (Sentinel::inRole('administrator'))
            <li class="first_group">
                <span class="space" ></span> 
                <a href="" class="line_height_45 admin_link open_menu" id="basic_links" >OPĆI PODACI  <span class="arrow_down"><i class="fas fa-chevron-down"></i></span></a>
            </li>
        @endif
            @if(Sentinel::getUser()->hasAccess(["roles.view"]))
                <li class="first_group basic_links"><span class="space" ></span> 
                    <a href="{{ route('roles.index') }}" class="line_height_45 admin_link {{ Request::is('roles*') ? 'active_admin' : '' }}" id="roles"> @lang('basic.roles')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["departments.view"]))
                <li class="first_group basic_links"><span class="space" ></span> 
                    <a href="{{ route('departments.index') }}" class="line_height_45 admin_link {{ Request::is('departments*') ? 'active_admin' : '' }}" id="departments">@lang('basic.departments')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["department_roles.view"]))
                <li class="first_group basic_links"><span class="space" ></span> 
                    <a href="{{ route('department_roles.index') }}" class="line_height_45 admin_link {{ Request::is('department_roles*') ? 'active_admin' : '' }}" id="department_roles">@lang('basic.department_roles')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["works.view"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('works.index') }}"  class="line_height_45 admin_link {{ Request::is('works*') ? 'active_admin' : '' }}" id="works">@lang('basic.works')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["instructions.create"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('instructions.index') }}" class="line_height_45 admin_link {{ Request::is('instructions*') ? 'active_admin' : '' }}" id="works">@lang('basic.instructions')</a>
                </li>
            @endif
            @if(in_array('Osposobljavanja', $moduli) && Sentinel::getUser()->hasAccess(["trainings.view"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('trainings.index') }}" class="line_height_45 admin_link {{ Request::is('trainings*') ? 'active_admin' : '' }}" id="employees">@lang('basic.trainings')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["absence_types.view"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('absence_types.index') }}"  class="line_height_45 admin_link {{ Request::is('absence_types*') ? 'active_admin' : '' }}" id="absence_types">@lang('absence.abs_types')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["work_tasks.view"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('work_tasks.index') }}"  class="line_height_45 admin_link {{ Request::is('work_tasks*') ? 'active_admin' : '' }}" id="work_tasks">@lang('basic.work_tasks')</a>
                </li>
            @endif
            @if(in_array('Oglasnik', $moduli) && Sentinel::getUser()->hasAccess(["ad_categories.view"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('ad_categories.index') }}"  class="line_height_45 admin_link {{ Request::is('ad_categories*') ? 'active_admin' : '' }}" id="ad_categories">@lang('basic.ad_categories')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["document_categories.view"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('document_categories.index') }}"  class="line_height_45 admin_link {{ Request::is('document_categories*') ? 'active_admin' : '' }}" id="document_categories">@lang('basic.document_categories')</a>
                </li>
            @endif
            @if(Sentinel::getUser()->hasAccess(["terminations.view"]))
                <li class="first_group basic_links"><span class="space" ></span>
                    <a href="{{ route('terminations.index') }}"  class="line_height_45 admin_link {{ Request::is('terminations*') ? 'active_admin' : '' }}" id="terminations">@lang('basic.termination_types')</a>
                </li>
            @endif
        @if(Sentinel::getUser()->hasAccess(["companies.view"]))
            <li class="">
                <span class="image_company" ></span> 
                <a href="{{ route('companies.index') }}" class="line_height_61 admin_link {{ Request::is('companies*') ? 'active_admin' : '' }}" id="companies">@lang('basic.company')</a>
            </li>
        @endif
        @if(Sentinel::getUser()->hasAccess(["emailings.view"]))
            <li class="">
                <span class="image_emailings" ></span> 
                <a href="{{ route('emailings.index') }}" class="line_height_61 admin_link {{ Request::is('emailings*') ? 'active_admin' : '' }}" id="emailings">@lang('basic.emailings')</a>
            </li>
        @endif
        @if(Sentinel::getUser()->hasAccess(["mail_templates.view"]))
            <li class="">
                <span class="space" ></span>
                <a href="{{ route('mail_templates.index') }}" class="line_height_45 admin_link {{ Request::is('mail_templates*') ? 'active_admin' : '' }}" id="mail_templates">@lang('basic.mail_templates')</a>
            </li> 
        @endif
        @if(Sentinel::getUser()->hasAccess(["templates.view"]))
            <li class="">
                <span class="image_template" ><i class="far fa-clone"></i></span> 
                <a href="{{ route('templates.index') }}" class="line_height_61 admin_link {{ Request::is('templates*') ? 'active_admin' : '' }}" id="templates">@lang('basic.templates')</a>
            </li>  
        @endif 
        @if(Sentinel::getUser()->hasAccess(["visitors.view"]))
            <li class="">
                <span class="image_template" ></span> 
                <a href="{{ route('visitors.index') }}" class="line_height_61 admin_link {{ Request::is('visitors*') ? 'active_admin' : '' }}" id="visitors">@lang('basic.visitors')</a>
            </li>  
        @endif 
    @endif
    @if(Sentinel::inRole('superadmin') )
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