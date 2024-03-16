<div class="form-group">
    {!! isset($label) ? '<label for="'. ($id ?? $name ?? "") . '" class="form-label" >'. ($label ?? '') . (isset($required) ? '<span class="text-danger">*</span>' : '<span class="text-info">*</span>')  . '</label>' : '' !!}
    @if (($type ?? 'text') == 'textarea')
        <textarea {{ $attributes->merge(['class' => 'form-control']) }} id="{{ $id ?? $name ?? "" }}" {{ ($disabled ?? false) ? 'disabled' : '' }}>{{ $value ?? '' }}</textarea>
    @elseif(($type ?? 'text') == 'select')
        <select name="{{$name}}" id="{{ $id ?? $name ?? "" }}" {{ ($disabled ?? false) ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'form-select']) }}>
            @if(!isset($url))
                <option value="" selected>Pilih {{ $label }}</option>
            @endif
            {{ $slot }}
        </select>
    @else
        <input {{ $attributes->merge(['class' => 'form-control']) }} id="{{ $id ?? $name ?? "" }}" {{ ($disabled ?? false) ? 'disabled' : '' }}>
    @endif
    @if (isset($name))
        <x-input-error :messages="$errors->get($name)" class="mt-1 text-danger" />
    @endif
</div>

@push('scripts')
    @if($type == 'select')
        <script>
            function initSelect2() {
                @if(isset($url))
                ZiApp.select2Search({
                    element: '#{{ $id ?? $name ?? "" }}',
                    url: '{{ $url ?? '' }}',
                    placeholder: '{{ $placeholder ?? 'Pilih ' . ($label ?? '') }}',
                    minimumInputLength: {{ $minimumInputLength ?? 3 }},
                    dropdownParent: '{{ $dropdownParent ?? '' }}',
                    allowClear: {{ $allowClear ?? 'true' }},
                })
                @elseif(!isset($url))
                $('#{{ $id ?? $name ?? "" }}').select2({
                    placeholder: '{{ $placeholder ?? 'Pilih ' . ($label ?? '') }}',
                    dropdownParent: '{{ $dropdownParent ?? '' }}',
                    allowClear: {{ $allowClear ?? 'true' }},
                })
                @endif
            }

            $(document).ready(function () {
                initSelect2()
            })
        </script>
    @endif
@endpush
