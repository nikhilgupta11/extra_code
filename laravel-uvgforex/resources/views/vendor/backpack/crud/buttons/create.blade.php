@if(Request::route()->getName() == 'currency.index' && backpack_user()->hasPermissionTo('Add Currency'))
@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
	@endif
@endif

@if(Request::route()->getName() == 'category.index' && backpack_user()->hasPermissionTo('Add Currency Category'))
@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
	@endif
@endif

@if(Request::route()->getName() == 'user.index' && backpack_user()->hasPermissionTo('Add User'))
@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
	@endif
@endif

@if(Request::route()->getName() == 'role.index' && backpack_user()->hasPermissionTo('Add Role'))
@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
	@endif
@endif

@if(Request::route()->getName() == 'page.index' && backpack_user()->hasPermissionTo('Add Page'))
@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
	@endif
@endif