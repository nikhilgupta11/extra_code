{{-- text input --}}

@include('crud::fields.inc.wrapper_start')
    @if(isset($field['label']) && $field['label'] == 'Converted USD value')
        <label>{!! $field['label'] !!}</label>
        <span title="Formula to convert USD from UVG is ((1 / Assign UVG value) *  Predefined USD value)"><img src="{{asset('/assets/info.svg')}}" class="img-fluid" style="width:13px;height:13px;margin-bottom:2px;"/></span>
    @else
        <label>{!! $field['label'] !!}</label>
    @endif
    @include('crud::fields.inc.translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::fields.inc.attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')
