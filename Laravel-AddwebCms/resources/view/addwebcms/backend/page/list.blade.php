@extends(config('addWebCms.layout'))

@section(config('addWebCms.display_section'))
    <a role="button" class="btn btn-primary mb-3" href="{{ route('admin.page.create-get') }}">New</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">name</th>
            <th scope="col">action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <th scope="row">{{ $page->id }}</th>
                <td>{{ $page->name }}</td>
                <td>
                    <a role="button" class="btn btn-primary" href="{{ route('admin.page.edit-get',['id' => $page->id]) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection