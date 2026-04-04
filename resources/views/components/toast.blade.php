@props(['type' => 'info', 'message' => '', 'duration' => 3000])

@php
$colors = [
    'success' => 'bg-green-600 border-green-400',
    'error' => 'bg-red-600 border-red-400',
    'info' => 'bg-blue-600 border-blue-400',
    'warning' => 'bg-yellow-600 border-yellow-400',
];

$icons = [
    'success' => '✅',
    'error' => '❌',
    'info' => 'ℹ️',
    'warning' => '⚠️',
];

$bgColor = $colors[$type] ?? $colors['info'];
$icon = $icons[$type] ?? $icons['info'];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, {{ $duration }})"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-20 right-4 z-50 max-w-sm w-full {{ $bgColor }} border-l-4 text-white shadow-lg rounded-lg pointer-events-auto overflow-hidden"
    role="alert"
>
    <div class="p-4 flex items-start gap-3">
        <span class="text-xl flex-shrink-0">{{ $icon }}</span>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
        <button 
            @click="show = false"
            class="flex-shrink-0 ml-4 text-white/70 hover:text-white transition-colors"
            aria-label="Fermer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <!-- Progress bar for auto-hide -->
    <div class="h-1 bg-white/20">
        <div 
            x-init="$el.style.animationDuration = '{{ $duration }}ms'; $el.classList.add('animate-shrink')"
            class="h-full bg-white/50 animate-shrink origin-left"
            style="animation: shrink {{ $duration }}ms linear forwards;"
        ></div>
    </div>
</div>

<style>
@keyframes shrink {
    from { transform: scaleX(1); }
    to { transform: scaleX(0); }
}
</style>
