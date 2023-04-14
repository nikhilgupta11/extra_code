@extends(backpack_view('blank'))

@php
    $Route = Request::route()->getName();
    $usd_value = DB::table('default_usd_value')->first();
    $predefindeValue = 1000;
    if($usd_value){
      $predefindeValue = $usd_value->predefined_usd_value;
    }
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
    </h2>
  </div>
@endsection

@section('content')
  {{-- Default box --}}
  <div class="row">

    {{-- THE ACTUAL CONTENT --}}
    <div class="{{ $crud->getListContentClass() }}">
        
        @if ( $crud->route == 'admin/currency' )
        <div class="row usd-value-wrap mb-0">
            <div class="col-md-8 col-sm-12 d-flex flex-md-row flex-column align-items-md-center">
                @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
                    <div class="d-print-none  {{ $crud->hasAccess('create')?'with-border':'' }}">
                        @include('crud::inc.button_stack', ['stack' => 'top'])
                    </div>
                @endif
                <label class="my-1 ml-md-4 mr-2 "><b>Predefined USD Value </b><img src="{{asset('/assets/info.svg')}}" class="img-fluid pl-1"  data-toggle="tooltip" data-placement="top" title="The Predefined USD Value entered is in context of 1 UVG." width="16" /> </label>
                <input type="text" value="{{ $predefindeValue }}" id="predefined_usd_value" name="predefined_usd_value"  data-html="true" class="usd-value form-control mx-md-2 my-md-0 my-1" >
                <div>
                  <button class="btn btn-primary" id="changeUSDValue">Submit</button>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 ">
                <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
            </div>
        </div>
        @else
            <div class="row mb-0">
                <div class="col-sm-6">
                    @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
                        <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">
                            @include('crud::inc.button_stack', ['stack' => 'top'])
                        </div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
                </div>
            </div>
        @endif
        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <table
          id="crudTable"
          class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2"
          data-responsive-table="{{ (int) $crud->getOperationSetting('responsiveTable') }}"
          data-has-details-row="{{ (int) $crud->getOperationSetting('detailsRow') }}"
          data-has-bulk-actions="{{ (int) $crud->getOperationSetting('bulkActions') }}"
          cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns() as $column)
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    data-column-name="{{ $column['name'] }}"
                    {{--
                    data-visible-in-table => if developer forced field in table with 'visibleInTable => true'
                    data-visible => regular visibility of the field
                    data-can-be-visible-in-table => prevents the column to be loaded into the table (export-only)
                    data-visible-in-modal => if column apears on responsive modal
                    data-visible-in-export => if this field is exportable
                    data-force-export => force export even if field are hidden
                    --}}

                    {{-- If it is an export field only, we are done. --}}
                    @if(isset($column['exportOnlyField']) && $column['exportOnlyField'] === true)
                      data-visible="false"
                      data-visible-in-table="false"
                      data-can-be-visible-in-table="false"
                      data-visible-in-modal="false"
                      data-visible-in-export="true"
                      data-force-export="true"
                    @else
                      data-visible-in-table="{{var_export($column['visibleInTable'] ?? false)}}"
                      data-visible="{{var_export($column['visibleInTable'] ?? true)}}"
                      data-can-be-visible-in-table="true"
                      data-visible-in-modal="{{var_export($column['visibleInModal'] ?? true)}}"
                      @if(isset($column['visibleInExport']))
                         @if($column['visibleInExport'] === false)
                           data-visible-in-export="false"
                           data-force-export="false"
                         @else
                           data-visible-in-export="true"
                           data-force-export="true"
                         @endif
                       @else
                         data-visible-in-export="true"
                         data-force-export="false"
                       @endif
                    @endif
                  >
                    {{-- Bulk checkbox --}}
                    @if($loop->first && $crud->getOperationSetting('bulkActions'))
                      {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ( $crud->buttons()->where('stack', 'line')->count() )
                  <th data-orderable="false"
                      data-priority="{{ $crud->getActionsColumnPriority() }}"
                      data-visible-in-export="false"
                      >{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          @if ( $crud->buttons()->where('stack', 'bottom')->count() )
          <div id="bottom_buttons" class="d-flex justify-content-between d-print-none text-center text-sm-left">
            @if ( $crud->route != 'admin/category' )
                @include('crud::inc.button_stack', ['stack' => 'bottom'])
            @else
                <span> &nbsp;</span>
            @endif

            <div id="datatable_button_stack" class="float-right text-right hidden-xs"></div>
          </div>
          @endif

    </div>

  </div>

@endsection

@section('after_styles')
  {{-- DATA TABLES --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

  {{-- CRUD LIST CONTENT - crud_list_styles stack --}}
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
  @include('crud::inc.datatables_logic')

  {{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
  @stack('crud_list_scripts')
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(function(){
        let Route = '{{$Route}}';
        let predefined_usd_value = '{{$predefindeValue}}';
        if(Route === 'currency.index'){
			$('#predefined_usd_value').attr('maxlength', 10);
            $('#predefined_usd_value').on("cut paste",function(e) {
				e.preventDefault();
			});
            $('#predefined_usd_value').on("keyup", function() {
				var rgx = /^[0-9]*\.?[0-9]*$/;
				if(!$('#predefined_usd_value').val().match(rgx)){
					$('#predefined_usd_value').val(predefined_usd_value);
					return false;
				}else{
					if($('#predefined_usd_value').val() >= 0){
                        $('#changeUSDValue').show();
					}else{
                        $('#predefined_usd_value').val(predefined_usd_value);
					}
				}
			});
            $('#predefined_usd_value').on("change", function() {
				if($('#predefined_usd_value').val() <= 0 || $('#predefined_usd_value').val() == ''){
					$('#predefined_usd_value').val(predefined_usd_value);
				}else{
          var val = $('#predefined_usd_value').val();
            const dec = val.split('.');
            if(dec[1]){
              const len = dec && dec.length > 2 ? 3 : dec.length;
              $('#predefined_usd_value').val(Number(val).toFixed(len));
            }
            $('#predefined_usd_value').val(Number(val));
                }
			});
            $('#changeUSDValue').on("click", function() { 
                if(predefined_usd_value == $('#predefined_usd_value').val()){
                    swal({
                        title: "Nothing to change. Please try with different USD values.",
                        buttons: "Close",
                        dangerMode: true,
                    });
                    return false;
                }else{
                    swal({
                        title: "Are you sure you want to change the predefined USD value?",
                        buttons: ["No", "Yes"],
                        dangerMode: true,
                    }).then((value) => {
                        if(value == true){
                            $.ajax({
                                type:'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url:"{{ route('update_usd_value') }}",
                                data: { USDValue: $('#predefined_usd_value').val() },
                                success:function(data){
                                    if(data == 1){
                                        location.reload();
                                    }else{
                                        swal({
                                            title: "Something went wrong. Please try again.",
                                            buttons: "Close",
                                            dangerMode: true,
                                        });
                                    }
                                }, error: function() {
                                    swal({
                                        title: "Something went wrong. Please try again.",
                                        buttons: "Close",
                                        dangerMode: true,
                                    });
                                }
                            });
                        }else{
                            $('#predefined_usd_value').val(predefined_usd_value);
                        }
                    });
                }
            });
        }
    });
</script>
