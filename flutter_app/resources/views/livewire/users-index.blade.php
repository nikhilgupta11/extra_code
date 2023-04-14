<div>
    <div class="row mt-4">
        <div class="col">
            <input type="text" class="form-control my-2" placeholder=" Search" wire:model="searchTerm" />

            <table class="table table-bordered table-hover table-responsive-sm w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('labels.backend.users.fields.first_name') }}</th>
                        <th>{{ __('labels.backend.users.fields.last_name') }}</th>
                        <th>{{ __('labels.backend.users.fields.email') }} Address</th>
                        <th>{{ __('labels.backend.users.fields.status') }}</th>
                        <th class="text-end">{{ __('labels.backend.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="btn-group">
                                    <button
                                        class="btn btn-sm {{ $user->status == 1 ? 'btn-success' : 'btn-danger' }} dropdown-toggle"
                                        type="button" data-coreui-toggle="dropdown"
                                        aria-expanded="false">{{ $user->status == 1 ? 'Active' : 'Inactive' }}</button>
                                    <ul class="dropdown-menu" style="">
                                        <li><a class="dropdown-item {{ $user->status == 1 ? 'active' : '' }}"
                                                @if ($user->status != 1) href="{{ route('backend.users.status', [$user->id, '1']) }}" @endif>Active</a>
                                        </li>
                                        <li><a class="dropdown-item {{ $user->status == 0 ? 'active' : '' }}"
                                                @if ($user->status != 0) href="{{ route('backend.users.status', [$user->id, '0']) }}" @endif>Inactive</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('backend.users.show', $user) }}" class="btn btn-success btn-sm mt-1"
                                    data-toggle="tooltip" title="{{ __('labels.backend.show') }}"><i
                                        class="fas fa-eye text-white"></i></a>
                                <a href="{{ route('backend.users.edit', $user) }}" class="btn btn-primary btn-sm mt-1"
                                    data-toggle="tooltip" title="{{ __('labels.backend.edit') }}"><i
                                        class="fas fa-edit"></i></a>
                                <a href="{{ route('backend.users.destroy', $user) }}"
                                    class="btn btn-danger btn-sm mt-1" data-method="DELETE"
                                    data-token="{{ csrf_token() }}" data-toggle="tooltip"
                                    title="{{ __('labels.backend.delete') }}" data-confirm="Are you sure?"><i
                                        class="fas fa-trash-alt text-white"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-7">
            <div class="float-left">
                {!! $users->total() !!} {{ __('labels.backend.total') }}
            </div>
        </div>
        <div class="col-5">
            <div class="float-end">
                {!! $users->links() !!}
            </div>
        </div>
    </div>
</div>
