@props([
    'hover' => false,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'card' . ($hover ? ' card-hover' : '') . ($padding ? ' card-body' : '')]) }}>
    {{ $slot }}
</div>
