<div class="text-start">
    <a href="{{ route('backend.users.show', $data) }}" class="btn btn-success btn-sm mt-1" data-toggle="tooltip"
        title="{{ __('labels.backend.show') }}"><i class="fas fa-eye text-white"></i></a>
    <a href="{{ route('backend.users.edit', $data) }}" class="btn btn-primary btn-sm mt-1" data-toggle="tooltip"
        title="{{ __('labels.backend.edit') }}"><i class="fas fa-edit"></i></a>
    <x-buttons.delete route='{!!route("backend.users.destroy", $data)!!}' class="text-white" method='DELETE' title="Delete" small="true" />
</div>
