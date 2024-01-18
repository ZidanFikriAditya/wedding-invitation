<div class="form-group">
    {!! isset($label) ? '<label for="'. ($id ?? $name ?? "") . '" class="form-label" >'. ($label ?? '') . (isset($required) ? '<span class="text-danger">*</span>' : '<span class="text-info">*</span>')  . '</label>' : '' !!}
    @if (($type ?? 'text') == 'textarea')
        <textarea {{ $attributes->merge(['class' => 'form-control']) }} id="{{ $id ?? $name ?? "" }}" {{ ($disabled ?? false) ? 'disabled' : '' }}>{{ $value ?? '' }}</textarea>
    @else
        <input {{ $attributes->merge(['class' => 'form-control']) }} id="{{ $id ?? $name ?? "" }}" {{ ($disabled ?? false) ? 'disabled' : '' }}>
    @endif
    @if (isset($name))
        <x-input-error :messages="$errors->get($name)" class="mt-1 text-danger" />
    @endif
</div>