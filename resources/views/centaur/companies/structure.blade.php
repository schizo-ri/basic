@extends('Centaur::admin')

@section('title', __('basic.company'))

@section('content')
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
            @php
           
            @endphp
            <header class="page-header">
                <div class="index_table_filter">
                    <label>
                        <input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
                    </label>
                    <a class="page_link" href="{{ route('structure') }}">Struktura firme</a>

                </div>
            </header>
	        <main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="structure">
                    @foreach($company->hasDepartments_level0 as $department0)
                        <div>
                            <h4 >{{ $department0->name }} <small>[level 0]</small></h4>
                            <div>
                                @foreach($company->hasDepartments_level1->where('level2',$department0->id )->sortBy('name')  as $department1)
                                    <div>
                                        <h5 class="collapsible">{{ $department1->name }} <i class="fas fa-chevron-down"></i> <small>[level 1]</small></h5>
                                        @if(count($company->hasDepartments_level2->where('level2',$department1->id )->sortBy('name') ) > 0)
                                            <div class="collaps_element">
                                                <span class="collapsible">Odjeli: <i class="fas fa-chevron-down"></i></span>
                                                @foreach($company->hasDepartments_level2->where('level2',$department1->id )->sortBy('name') as $department2)
                                                    <h6 >{{ $department2->name }} <small>[level 2]</small></h6>
                                                    @if( count($department2->hasWorks) >0)
                                                        <div class="collaps_element">
                                                            <span class="collapsible">Radna mjestra: <i class="fas fa-chevron-down"></i></span>
                                                            @foreach($department2->hasWorks->sortBy('name') as $work)
                                                                <p>{{ $work->name }}</p>
                                                                @if( count( $work->workers)->where('checkout',null)  >0 )
                                                                    <div class="dep_workers">
                                                                        <span class="collapsible">Djelatnici radnog mjesta: <i class="fas fa-chevron-down"></i></span>
                                                                        @foreach( $work->workers->where('checkout', null) as $employee)
                                                                            <p>{!! $employee->user ? $employee->user->first_name . ' ' .  $employee->user->last_name : $employee->email !!}</p>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    @if( count($department2->hasEmployeeDepartment) >0) 
                                                       <!--  <div class="collaps_element"> -->
                                                       <!--      <span class="collapsible">Djelatnici odjela: <i class="fas fa-chevron-down"></i></span> -->
                                                            @foreach($department2->hasEmployeeDepartment as $EmployeeDepartment)
                                                                @if($EmployeeDepartment->employee->checkout == null)
                                                                    <p>{!!   $EmployeeDepartment->employee->user->first_name . ' ' . $EmployeeDepartment->employee->user->last_name  !!}</p>
                                                                @endif
                                                            @endforeach
                                                       <!--  </div> -->
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        @if( count($department1->hasWorks) >0)
                                            <div class="collaps_element">
                                                <span class="collapsible">Radna mjestra: <i class="fas fa-chevron-down"></i></span>
                                                @foreach($department1->hasWorks->sortBy('name')  as $work)
                                                    <p>{{ $work->name }}</p>
                                                    @if( count( $work->workers->where('checkout',null)  ) >0 )
                                                        <div class="dep_workers">
                                                            <span class="collapsible">Djelatnici radnog mjesta: <i class="fas fa-chevron-down"></i></span>
                                                            @foreach( $work->workers->where('checkout',null) as $employee)
                                                                <p>{!! $employee->user ? $employee->user->first_name . ' ' .  $employee->user->last_name : $employee->email !!}</p>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        @if( count($department1->hasEmployeeDepartment) >0) 
                                            <div class="collaps_element">
                                                <span class="collapsible">Djelatnici odjela: <i class="fas fa-chevron-down"></i></span>
                                                @foreach($department1->hasEmployeeDepartment as $EmployeeDepartment)
                                                    @if($EmployeeDepartment->employee->checkout == null)
                                                        <p>{!!   $EmployeeDepartment->employee->user->first_name . ' ' . $EmployeeDepartment->employee->user->last_name  !!}</p>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @if( count($department0->hasWorks) >0)
                                    <div>
                                        <span class="collapsible">Radna mjestra: <i class="fas fa-chevron-down"></i></span>
                                        @foreach($department0->hasWorks->sortBy('name')  as $work)
                                            <p>{{ $work->name }}</p>
                                            @if( count( $work->workers)->where('checkout',null)  >0 )
                                                <div class="dep_workers">
                                                    <span class="collapsible">Djelatnici odjela: <i class="fas fa-chevron-down"></i></span>
                                                    @foreach( $work->workers->where('checkout',null) as $employee)
                                                    <p>{!! $employee->user ? $employee->user->first_name . ' ' .  $employee->user->last_name : $employee->email !!}</p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                @if( count($department0->hasEmployeeDepartment) >0) 
                                    <div class="collaps_element">
                                        <span class="collapsible">Djelatnici odjela: <i class="fas fa-chevron-down"></i></span>
                                        @foreach($department0->hasEmployeeDepartment as $EmployeeDepartment)
                                            @if($EmployeeDepartment->employee->checkout == null)
                                                <p>{!!   $EmployeeDepartment->employee->user->first_name . ' ' . $EmployeeDepartment->employee->user->last_name  !!}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </main>
		</div>
	</main>
@stop