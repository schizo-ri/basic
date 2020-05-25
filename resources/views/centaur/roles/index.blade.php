<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
			@if(Sentinel::getUser()->hasAccess(['roles.create']) || in_array('roles.create', $permission_dep))
				<a class="btn-new" href="{{ route('roles.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		<table id="index_table" class="display table table-hover">
			<thead>
				<tr>
					<th>@lang('basic.name')</th>
					<th>@lang('absence.mark')</th>
					<th>@lang('basic.permissions')</th>
					<th class="not-export-column">@lang('basic.options')</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($roles as $role)
				@php
					$role_permissions = implode(", ", array_keys($role->permissions));
					$role_permissions = explode(",",$role_permissions);

				@endphp
					<tr>
						<td>{{ $role->name }}</td>
						<td>{{ $role->slug }}</td>
						<td>
							@for ($i = 0; $i < count($role_permissions); $i++)
								<span class="role _{{ $i }}">{{ $role_permissions[$i] }}, </span>
							@endfor
							<span class="more">+ {{ count($role_permissions)-2 }} @lang('basic.more')</span>
							<span class="hide">@lang('basic.hide')</span>						
						</td>
						<td class="center">
							<!-- <button class="collapsible option_dots float_r"></button> -->
							@if(Sentinel::getUser()->hasAccess(['roles.update']) || in_array('roles.update', $permission_dep))
								<a href="{{ route('roles.edit', $role->id) }}"  rel="modal:open">
										<i class="far fa-edit"></i>
								</a>
							@endif
							@if(Sentinel::getUser()->hasAccess(['roles.delete']) || in_array('roles.delete', $permission_dep))
								@if (! $userRoleIds->contains($role->id))
									<a href="{{ route('roles.destroy', $role->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
										<i class="far fa-trash-alt"></i>
									</a>
								@endif
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</main>
<script>
	$(function(){
		$.getScript( '/../js/filter_table.js');
		
		/* $('.collapsible').click(function(event){        
       		$(this).siblings().toggle();
		}); */
		
		$('.more').click(function(){
			$( this ).siblings('.role').toggle();
			$( this ).hide();
			$( this ).siblings('.hide').show();
		});
		$('.hide').click(function(){
			$( this ).siblings('.role').hide();
			$( this ).siblings('.role._0').show();
			$( this ).siblings('.role._1').show();

			$( this ).siblings('.more').show();
			$( this ).hide();
		});
	});
	$.getScript( '/../restfulizer.js');
</script>