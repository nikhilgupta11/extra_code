@extends(backpack_view('blank'))

@php
	$Route = Request::route()->getName();
	$user = backpack_auth()->user();
    $userId = $user['id'];
	$default_usd_value = DB::table('default_usd_value')->pluck('predefined_usd_value')->toArray();
    $predefined_usd_value = $default_usd_value[0];
  	$defaultBreadcrumbs = [
		trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
		$crud->entity_name_plural => url($crud->route),
		trans('backpack::crud.add') => false,
  	];

  	// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  	$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')

<div class="row">
	<div class="{{ $crud->getCreateContentClass() }}">
		{{-- Default box --}}

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
			  {!! csrf_field() !!}
		      {{-- load the view from the application if it exists, otherwise load the one in the package --}}
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
		      @endif
                {{-- This makes sure that all field assets are loaded. --}}
                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
	          @include('crud::inc.form_save_buttons')
		  </form>
	</div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
	$(function(){
		let Route = '{{$Route}}';
		let predefined_usd_value = '{{$predefined_usd_value}}';
		if(Route === 'currency.create'){
			$('#exchange_rate').attr('maxlength', 7);
			$('#exchange_rate').on("cut paste",function(e) {
				e.preventDefault();
			});
			$('#exchange_rate').on("change", function() {
				if($('#exchange_rate').val() <= 0){
					$('#exchange_rate').val('');
					$('#exchange_rate_to_USD').val('');
				}else{
					if($('#exchange_rate').val() >= 0.01){
						$('#exchange_rate').val(parseFloat($('#exchange_rate').val()).toFixed(2));
						var temp_currency_val = 1 / $('#exchange_rate').val();
						var currency_val = temp_currency_val * predefined_usd_value;
						$('#exchange_rate_to_USD').val(currency_val.toFixed(2));
					}else{
						$('#exchange_rate_to_USD').val('');
					}
				}
			});
			$('#exchange_rate').on("keyup", function() {
				var rgx = /^[0-9]*\.?[0-9]*$/;
				if(!$('#exchange_rate').val().match(rgx)){
					$('#exchange_rate').val('');
					$('#exchange_rate_to_USD').val('');
					return false;
				}else{
					if($('#exchange_rate').val() >= 0.01){
						var temp_currency_val = 1 / $('#exchange_rate').val();
						var currency_val = temp_currency_val * predefined_usd_value;
						$('#exchange_rate_to_USD').val(currency_val.toFixed(2));
					}else{
						$('#exchange_rate_to_USD').val('');
					}
				}
			});
		}
	});
</script>