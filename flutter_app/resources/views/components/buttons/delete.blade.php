@props(["route"=>"", "icon"=>"fas fa-trash", "title", "small"=>"", "class"=>"",'method'=>""])

@if($method != "")
<form action="{{$route}}" method="post" style="display: contents;" @if ($method == 'DELETE') onsubmit="return confirm('Are you sure want to delete this data')" @endif>
@csrf
@method($method)
<button type="submit"
    class='btn btn-danger {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</button>
</form>
@elseif($route)
<a href='{{$route}}'
    class='btn btn-danger {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</a>
@else
<button type="submit"
    class='btn btn-danger {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</button>
@endif
