<div class="text-start">
    @php
        if(!isset($edit)){
            $edit = true;
        }
        if(!isset($delete)){
            $delete = true;
        }
        if(!isset($show)){
            $show = true;
        }
    @endphp
    @if(Route::has("backend.$module_name.show") && $show == true)
    <x-buttons.show route='{!!route("backend.$module_name.show", $data)!!}' title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif

    @if(Route::has("backend.$module_name.edit") && $edit == true)
    <x-buttons.edit route='{!!route("backend.$module_name.edit", $data)!!}' title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif

    @if ($softDeleteAction == false && Route::has("backend.$module_name.force_destroy"))
    <x-buttons.delete route='{!!route("backend.$module_name.force_destroy", $data)!!}' title="{{__('Force Delete')}} {{ ucwords(Str::singular($module_name)) }}" method='DELETE' small="true" />
    @elseif(Route::has("backend.$module_name.destroy") && $delete == true)
    <x-buttons.delete route='{!!route("backend.$module_name.destroy", $data)!!}' method='DELETE' title="{{__('Delete')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif
</div>
