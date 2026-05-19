@props([
    'title' => '',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'section-header']) }}>
    <div>
        <h2 class="section-title">{{ $title }}</h2>
        @if($description)
            <p class="text-sm text-charcoal-500 mt-1">{{ $description }}</p>
        @endif
    </div>
    
    @if(isset($actions))
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
