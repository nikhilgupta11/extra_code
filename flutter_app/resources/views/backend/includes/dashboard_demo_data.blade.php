<div class="row">
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-primary text-white p-3 me-3">
                    <svg class="icon icon-xl">
                        <use xlink:href="/fonts/free.svg#cil-user"></use>
                    </svg>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-primary"><a target="_blank" href="{{route('backend.users.index')}}">{{$totalUsers}}</a></div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Total Users</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a target="_blank" href="{{route('backend.users.index')}}" class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">View More</span>
                    <svg class="icon">
                        <use xlink:href="/fonts/free.svg#cil-chevron-right"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-danger text-white p-3 me-3">
                    <div class="icon icon-xl">
                        <i class="fa fa-road"></i>
                    </div>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-danger"><a target="_blank" href="{{route('backend.trips.index')}}">{{$totalTrips}}</a></div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Total Trips</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a target="_blank" href="{{route('backend.trips.index')}}" class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">View More</span>
                    <svg class="icon">
                        <use xlink:href="/fonts/free.svg#cil-chevron-right"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-secondary text-white p-3 me-3">
                    <svg class="icon icon-xl">
                        <use xlink:href="/fonts/free.svg#cil-clipboard"></use>
                    </svg>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-secondary"><a target="_blank" href="{{route('backend.enquiries.index')}}">{{$totalQueries}}</a></div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Total Enquiries</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a target="_blank" href="{{route('backend.enquiries.index')}}" class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">View More</span>
                    <svg class="icon">
                        <use xlink:href="/fonts/free.svg#cil-chevron-right"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-info text-white p-3 me-3">
                    <svg class="icon icon-xl">
                        <use xlink:href="/fonts/free.svg#cil-list"></use>
                    </svg>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-info"><a target="_blank" href="{{route('backend.projects.index')}}">{{$totalProjects}}</a></div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Total Projects</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a target="_blank" href="{{route('backend.projects.index')}}" class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">View More</span>
                    <svg class="icon">
                        <use xlink:href="/fonts/free.svg#cil-chevron-right"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-warning text-white p-3 me-3">
                    <i class="icon icon-xl fas fa-file-alt"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-warning"><a target="_blank" href="{{route('backend.posts.index')}}">{{$totalBlogs}}</a></div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Total Blogs</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a target="_blank" href="{{route('backend.posts.index')}}" class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">View More</span>
                    <svg class="icon">
                        <use xlink:href="/fonts/free.svg#cil-chevron-right"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-success text-white p-3 me-3">
                    <svg class="icon icon-xl">
                        <use xlink:href="/fonts/free.svg#cil-file"></use>
                    </svg>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-success"><a target="_blank" href="{{route('backend.pages.index')}}">{{$totalPages}}</a></div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Total Pages</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a target="_blank" href="{{route('backend.pages.index')}}" class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">View More</span>
                    <svg class="icon">
                        <use xlink:href="/fonts/free.svg#cil-chevron-right"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>