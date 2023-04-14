<table class="table table-responsive-sm table-hover table-bordered">
    <?php
    $all_columns = $$module_name_singular->getTableColumns();
    ?>
    <thead>
        <tr>
            <th scope="col">
                <strong>
                    @lang('Name')
                </strong>
            </th>
            <th scope="col">
                <strong>
                    @lang('Value')
                </strong>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($all_columns as $column)
        @php
        if (isset($excludes) && in_array($column->Field,$excludes) || $column->Field == 'deleted_at') {
            continue;
        }
        if($column->Field == 'status'){
            $isActive = '';
            $isInactive = '';
            $isActiveRoute = '';
            $isInactiveRoute = '';
            if($$module_name_singular->status == 1){
                $btn = 'btn-success';
                $text = 'Active';
                $isActive = 'active';
                $isInactiveRoute = 'href="'.route('backend.'.$module_name.'.status',[$$module_name_singular->id,0]).'"';
            }else{
                $btn = 'btn-danger';
                $text = 'Inactive';
                $isInactive = 'active';
                $isActiveRoute = 'href="'.route('backend.'.$module_name.'.status',[$$module_name_singular->id,1]).'"';
            }
            $$module_name_singular->status = 
            '<div class="btn-group">
                <button class="btn btn-sm '.$btn.' dropdown-toggle" type="button"
                    data-coreui-toggle="dropdown" aria-expanded="false">'.$text.'</button>
                <ul class="dropdown-menu" style="">
                    <li><a class="dropdown-item '.$isActive.'" '.$isActiveRoute.'>Active</a></li>
                    <li><a class="dropdown-item '.$isInactive.'" '.$isInactiveRoute.'>Inactive</a></li>
                </ul>
            </div>'; 
        }
        @endphp
        <tr>
            <td>
                <strong>
                    {{ __(label_case($column->Field)) }}
                </strong>
            </td>
            <td>
                {!! show_column_value($$module_name_singular, $column) !!}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Lightbox2 Library --}}
<x-library.lightbox />