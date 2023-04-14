@extends(config('addWebCms.layout'))

@section(config('addWebCms.display_section'))
    <a role="button" class="btn btn-primary mb-3" href="{{ route('admin.component.create-get') }}">New</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">name</th>
            <th scope="col">action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($components as $component)
            <tr>
                <th scope="row">{{ $component->id }}</th>
                <td>{{ $component->name }}</td>
                <td>
                    <a role="button" class="btn btn-primary" href="{{ route('admin.component.edit-get',['id' => $component->id]) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection