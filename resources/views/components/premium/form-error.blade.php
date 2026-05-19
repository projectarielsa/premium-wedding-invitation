@props([
    'name' => '',
])

@error($name)
    <p {{ $attributes->merge(['class' => 'form-error']) }}>{{ $message }}</p>
@enderror
