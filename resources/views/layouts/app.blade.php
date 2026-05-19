<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Wedding Invite') }} - Premium Wedding Invitation Platform</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23D4AF37'><path d='M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'/></svg>">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-body antialiased bg-ivory-100">
        <div 
            x-data="{ 
                sidebarOpen: false,
                init() {
                    this.sidebarOpen = window.innerWidth >= 1024;
                }
            }" 
            class="min-h-full"
        >
            {{-- Mobile Sidebar Overlay --}}
            <div 
                x-show="sidebarOpen" 
                x-on:click="sidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-charcoal-900/50 backdrop-blur-sm z-40 lg:hidden"
                x-cloak
            ></div>

            {{-- Sidebar --}}
            <aside 
                x-show="sidebarOpen"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-ivory-200 flex flex-col lg:translate-x-0"
                :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
                x-cloak
            >
                {{-- Logo Area --}}
                <div class="flex items-center justify-between h-16 px-6 border-b border-ivory-200">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <x-application-logo />
                    </a>
                    <button 
                        x-on:click="sidebarOpen = false" 
                        class="lg:hidden p-2 rounded-lg text-charcoal-500 hover:bg-ivory-100 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    {{-- Main Navigation --}}
                    <div class="mb-8">
                        <p class="px-4 mb-3 text-xs font-semibold text-charcoal-400 uppercase tracking-wider">Main Menu</p>
                        
                        {{-- Dashboard --}}
                        <a 
                            href="{{ route('dashboard') }}" 
                            class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        {{-- Invitations --}}
                        <a 
                            href="{{ route('invitations.index') }}" 
                            class="nav-link {{ request()->routeIs('invitations.*') ? 'nav-link-active' : '' }}"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Invitations</span>
                        </a>
                    </div>

                    {{-- Settings Navigation --}}
                    <div>
                        <p class="px-4 mb-3 text-xs font-semibold text-charcoal-400 uppercase tracking-wider">Settings</p>

                        {{-- Profile --}}
                        <a 
                            href="{{ route('profile.edit') }}" 
                            class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profile</span>
                        </a>
                    </div>
                </nav>

                {{-- User Profile Section --}}
                <div class="border-t border-ivory-200 p-4">
                    <div x-data="{ open: false }" class="relative">
                        <button 
                            x-on:click="open = !open"
                            class="flex items-center gap-3 w-full p-3 rounded-xl hover:bg-ivory-100 transition-colors"
                        >
                            <x-premium.avatar :name="Auth::user()->name" size="md" />
                            <div class="flex-1 text-left min-w-0">
                                <p class="text-sm font-medium text-charcoal-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-charcoal-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <svg class="w-4 h-4 text-charcoal-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        {{-- User Dropdown --}}
                        <div 
                            x-show="open" 
                            x-on:click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl border border-ivory-200 shadow-soft-lg py-2"
                            x-cloak
                        >
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Settings</span>
                            </a>
                            <hr class="my-2 border-ivory-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-item-danger w-full text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <div class="lg:pl-72">
                {{-- Top Header Bar --}}
                <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-ivory-200">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        {{-- Mobile Menu Button --}}
                        <button 
                            x-on:click="sidebarOpen = true" 
                            class="lg:hidden p-2 rounded-lg text-charcoal-600 hover:bg-ivory-100 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        {{-- Page Title (Mobile) --}}
                        <div class="lg:hidden">
                            <a href="{{ route('dashboard') }}">
                                <x-application-logo />
                            </a>
                        </div>

                        {{-- Search Bar (Desktop) --}}
                        <div class="hidden lg:flex flex-1 max-w-md">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input 
                                    type="search" 
                                    placeholder="Search invitations, guests..." 
                                    class="w-full pl-10 pr-4 py-2 bg-ivory-100 border-0 rounded-xl text-sm text-charcoal-700 placeholder-charcoal-400 focus:ring-2 focus:ring-gold-500/20 focus:bg-white transition-colors"
                                >
                            </div>
                        </div>

                        {{-- Right Actions --}}
                        <div class="flex items-center gap-2">
                            {{-- Quick Create Button --}}
                            <a 
                                href="{{ route('invitations.create') }}" 
                                class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-gold-500 text-white text-sm font-medium rounded-xl hover:bg-gold-600 transition-colors shadow-soft"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>New Invitation</span>
                            </a>

                            {{-- Notifications --}}
                            <button class="relative p-2 rounded-xl text-charcoal-500 hover:bg-ivory-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                {{-- Notification Badge --}}
                                <span class="absolute top-1 right-1 w-2 h-2 bg-gold-500 rounded-full"></span>
                            </button>

                            {{-- Mobile Create Button --}}
                            <a 
                                href="{{ route('invitations.create') }}" 
                                class="sm:hidden p-2 rounded-xl text-charcoal-500 hover:bg-ivory-100 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </header>

                {{-- Main Content Area --}}
                <main class="p-4 sm:p-6 lg:p-8">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <x-premium.toast type="success">{{ session('success') }}</x-premium.toast>
                    @endif
                    
                    @if(session('error'))
                        <x-premium.toast type="error">{{ session('error') }}</x-premium.toast>
                    @endif
                    
                    @if(session('warning'))
                        <x-premium.toast type="warning">{{ session('warning') }}</x-premium.toast>
                    @endif
                    
                    @if(session('info'))
                        <x-premium.toast type="info">{{ session('info') }}</x-premium.toast>
                    @endif

                    {{ $slot }}
                </main>

                {{-- Footer --}}
                <footer class="border-t border-ivory-200 bg-white">
                    <div class="px-4 sm:px-6 lg:px-8 py-6">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-charcoal-500">
                                &copy; {{ date('Y') }} Wedding Invite. All rights reserved.
                            </p>
                            <div class="flex items-center gap-6">
                                <a href="#" class="text-sm text-charcoal-500 hover:text-charcoal-700 transition-colors">Help</a>
                                <a href="#" class="text-sm text-charcoal-500 hover:text-charcoal-700 transition-colors">Privacy</a>
                                <a href="#" class="text-sm text-charcoal-500 hover:text-charcoal-700 transition-colors">Terms</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
