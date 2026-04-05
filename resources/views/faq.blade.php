@extends('layouts.app')

@section('title', 'FAQ - Questions fréquentes')

@section('content')
<div class="min-h-screen bg-slate-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-500 to-amber-700 rounded-full mb-6 shadow-lg shadow-amber-500/20">
                <svg class="w-10 h-10 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">FAQ</h1>
            <p class="text-xl text-slate-400">Questions fréquentes sur nos vinyles personnalisés</p>
        </div>

        {{-- FAQ Accordion --}}
        <div class="space-y-4" x-data="{ active: null }">
            @foreach($faqItems as $index => $item)
            <div class="bg-slate-800 rounded-xl overflow-hidden border border-slate-700/50 hover:border-slate-600 transition-all duration-300">
                <button 
                    @click="active === {{ $index }} ? active = null : active = {{ $index }}"
                    class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:ring-inset"
                    :class="{ 'bg-slate-700/50': active === {{ $index }} }"
                >
                    <span class="text-lg font-medium text-white pr-4">{{ $item['question'] }}</span>
                    <span 
                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-slate-700 text-amber-500 transition-transform duration-300"
                        :class="{ 'rotate-180': active === {{ $index }} }"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </button>
                <div 
                    x-show="active === {{ $index }}"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                >
                    <div class="pt-2 text-slate-300 leading-relaxed border-t border-slate-700/50">
                        {{ $item['answer'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Contact CTA --}}
        <div class="mt-12 text-center">
            <p class="text-slate-400 mb-4">Vous ne trouvez pas votre réponse ?</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-900 font-semibold rounded-lg hover:from-amber-400 hover:to-amber-500 transition-all duration-300 shadow-lg shadow-amber-500/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Contactez-nous
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush