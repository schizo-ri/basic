@extends('Centaur::admin')

@section('title', __('basic.employees'))
	@section('content')
	<header class="page-header">
		<div class="index_table_filter col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['employees.create']) || in_array('employees.create', $permission_dep))
				<a class="btn-new" href="{{ route('employees.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<div class="div_select2">
				<select id="filter_types" class="select_filter filter_checkout" >
					<option value="checkin" selected>Prijavljeni</option>
					<option value="checkout"  >Odjavljeni</option>
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 visibilyti_colums">
			<span>Vidljivost kolona: </span>
			<a class="toggle-vis" data-column="0">Ime i prezime</a>
			<a class="toggle-vis" data-column="1">ERP ID</a>
			<a class="toggle-vis" data-column="2">Datum rođenja</a>
			<a class="toggle-vis" data-column="3">Mjesto rođenja</a>
			<a class="toggle-vis" data-column="4">OIB</a>
			<a class="toggle-vis" data-column="5">OI</a>
			<a class="toggle-vis" data-column="6">Istek OI</a>
			<a class="toggle-vis" data-column="7">Radno mjesto</a>
			<a class="toggle-vis" data-column="8">Odjel</a>
			<a class="toggle-vis" data-column="9">Stručna sprema</a>
			<a class="toggle-vis" data-column="10">Struka</a>
			<a class="toggle-vis" data-column="11">Datum prijave</a>
			<a class="toggle-vis" data-column="12">Mobitel</a>
			<a class="toggle-vis" data-column="13">Email</a>
			<a class="toggle-vis" data-column="14">Prebivalište</a>
			<a class="toggle-vis" data-column="15">Stranac</a>
			<a class="toggle-vis" data-column="16">Liječnički pregled</a>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12 employees_main">
		<div class="table-responsive">
			@if(count($employees))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th >@lang('basic.fl_name')</th>
							<th class='col_hidden'>ERP ID</th>
							<th class="sort_date col_hidden">@lang('basic.b_day')</th>
							<th  class='col_hidden'>Mjesto rođenja</th>
							<th  class='col_hidden'>OIB</th>
							<th  class='col_hidden'>Osobna iskaznica</th>
							<th class="sort_date col_hidden">Istek osobne iskaznice</th>
							<th >@lang('basic.work')</th>
							<th>@lang('basic.department')</th>
							<th class='col_hidden'>Stručna sprema</th>
							<th class='col_hidden'>Struka</th>
							<th class="sort_date">@lang('basic.reg_date')</th>
							<th class='col_hidden'>Mobitel</th>
							<th class='col_hidden'>E-mail</th>
							<th class='col_hidden'>Prebivalište</th>
							<th class='col_hidden'>Stranac</th>
							<th class='sort_date col_hidden'>Liječnički pregled</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($employees as $employee)
							<tr class="tr_open_link "  data-href="/employees/{{ $employee->id }}" data-modal >
								<td>{{ $employee->user['last_name'] . ' ' . $employee->user['first_name'] }}
									<span class="employee_color" {!! $employee->color ? 'style="background-color:' . $employee->color . '"' : '' !!}>
									</span>
								</td>
								<td>{!! $employee->erp_id ? $employee->erp_id : '' !!}</td>
								<td>{!! $employee->b_day ? date("d.m.Y",strtotime($employee->b_day)) : '' !!}</td>
								<td>{{ $employee->b_place }}</td>
								<td>{{ strval($employee->oib) }}</td>
								<td>{{ strval($employee->oi) }}</td>
								<td>{{ date("d.m.Y",strtotime($employee->oi_expiry))  }}</td>
								<td>{!! $employee->work ? $employee->work['name'] : '' !!}</td>
								<td>
									@if($employee->hasEmployeeDepartmen && count($employee->hasEmployeeDepartmen)>0)
										@foreach ( $employee->hasEmployeeDepartmen as $empl_department )
											{!! $empl_department->department ? $empl_department->department->name . ' [' .  $empl_department->department->level1 . ']' : '' !!} <br>
										@endforeach
									@endif
								</td>
								<td>{{ $employee->qualifications }}</td>
								<td>{{ $employee->title }}</td>
								<td>{!! $employee->reg_date ? date("d.m.Y",strtotime($employee->reg_date)) : '' !!}</td>
								<td>{!! $employee->mobile ? 'Poslovni: ' . $employee->mobile : ''  !!} {!! 'Privatan: ' . $employee->priv_mobile ? $employee->priv_mobile : ''  !!}</td>
								<td>{{ $employee->email }}</td>
								<td>{{ $employee->prebiv_adresa . ', ' . $employee->prebiv_grad }}</td>
								<td >{!! $employee->stranger == 1 ? 'DA, dozvola do:' . 	 date("d.m.Y",strtotime($employee->permission_date))  : '' !!}</td>
								<td >{!! $employee->lijecn_pregled ?  date("d.m.Y",strtotime($employee->lijecn_pregled)) : '' !!}</td>
								<td class="center not_link">
									
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['employees.update']) || in_array('employees.update', $permission_dep))
										<a href="{{ route('employees.edit', $employee->id) }}" title="{{ __('basic.edit_employee') }}"  rel="modal:open">
											<i class="fas fa-user-cog"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['users.update']) &&  $employee->user_id)
										<a href="{{ route('users.edit', $employee->user_id) }}" class="" title="{{ __('basic.edit_user') }}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::inRole('superadmin'))
										<a href="{{ route('employees.destroy', $employee->id ) }}" style="display:none" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep))
										<a href="{{ route('absences.show', $employee->id) }}" title="{{ __('absence.absences') }}">
											<i class="fas fa-suitcase"></i>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
	<div id="login-modal" class="modal modal_user modal_employee">
		
	</div>
	<script>
		$(function(){
			$( "thead tr th" ).each(function( index ) {
				if( ! $(this).hasClass("col_hidden") ) {
					$( 'a.toggle-vis[data-column='+index+']').addClass('col_visible');
				}
			});
			$( 'a.toggle-vis').on('click',function(){
				if( $( this ).hasClass('col_visible')) {
					$( this ).removeClass('col_visible');
				} else {
					$( this ).addClass('col_visible');
				}
			});
		});
	</script>
@stop