@props([
    'title' => '',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'page-header']) }}>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            @if($description)
                <p class="page-description">{{ $description }}</p>
            @endif
        </div>
        
        @if(isset($actions))
            <div class="flex items-center gap-3">
                {{ $actions }}
            </div>
        @endif
    </div>
    
    {{ $slot }}
</div>
