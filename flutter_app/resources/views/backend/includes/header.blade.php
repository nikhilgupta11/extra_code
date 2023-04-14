<?php
$notifications = optional(auth()->user())->unreadNotifications;
$notifications_count = optional($notifications)->count();
$notifications_latest = optional($notifications)->take(5);
?>

<header class="header header-sticky mb-4">
    <div class="container-fluid">
        <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <svg class="icon icon-lg">
                <use xlink:href="/fonts/free.svg#cil-menu"></use>
            </svg>
        </button>
        <a class="header-brand d-md-none" href="#">
            <img class="sidebar-brand-full" src="{{asset('img/final-logo.png')}}" height="46" alt="{{ app_name() }}">
        </a>
        <ul class="header-nav ms-auto align-items-center">
            <li>Hey, {{auth()->user()->name}}</li>
            <li class="nav-item dropdown">
                <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md">
                        <img class="avatar-img" src="{{ asset(auth()->user()->avatar) }}" alt="{{ asset(auth()->user()->name) }}">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-light py-2">
                        <div class="fw-semibold">{{ __('Account') }}</div>
                    </div>
                    <a class="dropdown-item" href="{{route('backend.users.profile', Auth::user()->id)}}">
                        <i class="cil-user me-2"></i>&nbsp;Profile
                    </a>

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="cil-account-logout me-2"></i>&nbsp;
                        @lang('Logout')
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>

                </div>
            </li>
        </ul>
    </div>

    <div class="header-divider"></div>

    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0 ms-2">
                @yield('breadcrumbs')
            </ol>
        </nav>
        <div class="d-flex flex-row float-end">
            <div class="">{{ date_today() }}&nbsp;</div>
            <div id="liveClock" class="clock" onload="showTime()"></div>
        </div>
    </div>
</header>

@push('after-scripts')
<script type="text/javascript">
    $(function() {
        // Show the time
        showTime();
    })

    function showTime() {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();

        var session = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        var time = hours + ":" + minutes + ":" + seconds + " " + session;
        document.getElementById("liveClock").innerText = time;
        document.getElementById("liveClock").textContent = time;

        setTimeout(showTime, 1000);
    }
</script>
@endpush
