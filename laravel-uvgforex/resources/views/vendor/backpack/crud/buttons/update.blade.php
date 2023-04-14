@if ($crud->hasAccess('update'))
	@if (!$crud->model->translationEnabled())

	{{-- Single edit button --}}
	@if((Request::route()->getName() == 'currency.search' || Request::route()->getName() == 'currency.show') && backpack_user()->hasPermissionTo('Edit Currency'))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	@endif

	@if((Request::route()->getName() == 'category.search' || Request::route()->getName() == 'category.show') && backpack_user()->hasPermissionTo('Edit Currency Category'))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	@endif

	@if((Request::route()->getName() == 'user.search' || Request::route()->getName() == 'user.show') && backpack_user()->hasPermissionTo('Edit User'))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	@endif

	@if(Request::route()->getName() == 'role.search' && backpack_user()->hasPermissionTo('Edit Role'))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	@endif

	{{-- @if(Request::route()->getName() == 'page.search' && backpack_user()->hasPermissionTo('Edit Page'))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	@endif --}}
	@else

	{{-- Edit button group --}}
	<div class="btn-group">
	  <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link pr-0"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	  <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu dropdown-menu-right">
  	    <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
	  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
		  	<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?_locale={{ $key }}">{{ $locale }}</a>
	  	@endforeach
	  </ul>
	</div>

	@endif
@endif
