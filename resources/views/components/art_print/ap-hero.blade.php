{{-- resources/views/components/art_print/ap-hero.blade.php --}}
{{-- Hero section style galerie d'art --}}

@props(['label' => '', 'title' => '', 'subtitle' => '', 'light' => '', 'buttons' => []])

<section class="ap-hero">
    <div class="ap-container">
        <div class="ap-hero-content">
            @if($label)
                <p class="ap-hero-label">{{ $label }}</p>
            @endif
            
            <h1>
                {{ $title }}
                @if($light)
                    <br><span class="light">{{ $light }}</span>
                @endif
            </h1>
            
            @if($subtitle)
                <p>{{ $subtitle }}</p>
            @endif
            
            @if(!empty($buttons))
                <div class="ap-btn-group">
                    @foreach($buttons as $button)
                        <a href="{{ $button['url'] ?? '#' }}" 
                           class="ap-btn {{ $button['style'] ?? 'ap-btn-dark' }}"
                           @if(isset($button['onclick'])) onclick="{{ $button['onclick'] }}" @endif>
                            {{ $button['text'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
