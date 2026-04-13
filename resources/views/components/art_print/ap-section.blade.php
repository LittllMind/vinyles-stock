{{-- resources/views/components/art_print/ap-section.blade.php --}}
{{-- Section avec titre et compteur --}}

@props(['title' => '', 'count' => '', 'narrow' => false])

<div class="ap-section-header">
    <h2 class="ap-section-title">{{ $title }}</h2>
    @if($count)
        <span class="ap-section-count">{{ $count }}</span>
    @endif
</div>

<section class="ap-section" style="padding-top: {{ $narrow ? '2rem' : '4rem' }};">
    {{ $slot }}
</section>
