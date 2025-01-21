@component($typeForm, get_defined_vars())
    <div data-controller="icon-select"
        data-select-placeholder="{{$attributes['placeholder'] ?? ''}}"
        data-select-allow-empty="{{ $allowEmpty }}"
        data-select-message-notfound="{{ __('No results found') }}"
        data-select-allow-add="{{ var_export($allowAdd, true) }}"
        data-select-message-add="{{ __('Add') }}"
        class="icon-select-field"
    >
        <select {{ $attributes }}>
            @foreach($options as $key => $option)
                <option value="{{ $key }}"
                        @isset($value)
                            @if (is_array($value) && in_array($key, $value)) selected
                            @elseif (isset($value[$key]) && $value[$key] == $option) selected
                            @elseif ($key == $value) selected
                            @endif
                        @endisset
                >{{ $option }}</option>
            @endforeach
        </select>
    </div>
    @once
        <x-svg-sprite/>
    @endonce
@endcomponent
