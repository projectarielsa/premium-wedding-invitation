{{-- 
    Public Invitation Show
    Routes to the appropriate template based on invitation's template settings
--}}
@php
    // Determine which template to use
    $templateSlug = $invitation->template?->slug ?? $invitation->theme_settings['template'] ?? 'elegant-luxury';
    
    // Map template slugs to view files
    $templateMap = [
        'elegant-luxury' => 'public.invitations.templates.elegant-luxury',
        'minimal-white' => 'public.invitations.templates.minimal-white',
        'modern-dark' => 'public.invitations.templates.modern-dark',
    ];
    
    // Get the template view or fall back to elegant-luxury
    $templateView = $templateMap[$templateSlug] ?? 'public.invitations.templates.elegant-luxury';
@endphp

@include($templateView, [
    'invitation' => $invitation,
    'guest' => $guest ?? null,
    'isPreview' => $isPreview ?? false,
])
