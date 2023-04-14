<li class='nav-item'><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="nav-icon la la-dashboard"></i> <span>Dashboard</span></a></li>
{{-- @if(backpack_user()->hasPermissionTo('View Page Bar'))
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-file-o'></i> <span>Pages</span></a></li>
@endif --}}
<li class='nav-item'>
    <a class="nav-link" href="{{ route('admin.component.list') }}">
        <i class="nav-icon la la-dashboard"></i> <span>Component</span>
    </a>
</li>
<li class='nav-item'>
    <a class="nav-link" href="{{ route('admin.page.list') }}">
        <i class="nav-icon la la-dashboard"></i> <span>Pages</span>
    </a>
</li>
<!-- Users, Roles, Permissions -->
@if(backpack_user()->hasPermissionTo('View Currency Bar'))
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-money"></i> Currency</a>
    <ul class="nav-dropdown-items"> 
        @if(backpack_user()->hasPermissionTo('View Currency'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('currency') }}"><i class="nav-icon la la-money"></i> Currency</a></li>
        @endif
        @if(backpack_user()->hasPermissionTo('View Currency Category'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-money"></i> Currency Categories</a></li>
        @endif
    </ul>
</li>
@endif
@if(backpack_user()->hasPermissionTo('View Authentication Bar'))
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
    <ul class="nav-dropdown-items">
        @if(backpack_user()->hasPermissionTo('View User'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
        @endif
        @if(backpack_user()->hasPermissionTo('View Role'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
        @endif
        @if(backpack_user()->hasPermissionTo('View Permission'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
        @endif
    </ul>
</li>
@endif
@if(backpack_user()->hasRole('Admin'))
@if(backpack_user()->hasPermissionTo('View Advance'))
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i> Advanced</a>
    <ul class="nav-dropdown-items">
        @if(backpack_user()->hasPermissionTo('View Settings'))
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> <span>Settings</span></a></li>
        @endif
        @if(backpack_user()->hasPermissionTo('View Logs'))
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-terminal'></i> Logs</a></li>
        @endif
        @if(backpack_user()->hasPermissionTo('View File Manager'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
        @endif
        @if(backpack_user()->hasPermissionTo('View Backup'))
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i> Backups</a></li>
        @endif
    </ul>
</li>
@endif
@endif