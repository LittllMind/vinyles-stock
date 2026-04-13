{{-- resources/views/components/art_print/ap-text-block.blade.php --}}
{{-- Bloc de texte centré style galerie --}}

@props(['title' => '', 'content' => ''])

<div class="ap-text-block">
    @if($title)
        <h3>{{ $title }}</h3>
    @endif
    <p>{{ $content }}</p>
</div>
