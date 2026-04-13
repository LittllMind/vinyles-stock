{{-- resources/views/components/art-print/ap-footer.blade.php --}}
{{-- Footer minimaliste galerie --}}

<footer class="ap-footer">
    <div class="ap-footer-content">
        <p>
            {{ date('Y') }} ART PRINT • Galerie Vinyle
            <span class="ap-footer-url" style="font-size: 0.65rem;">
                @if(is_theme('art-print'))
                    <a href="{{ route('theme.switch', 'vinyl-cult') }}" style="text-decoration: underline; color: inherit;">Passer au thème Vinyl-Cult</a>
                @else
                    <a href="{{ route('theme.switch', 'art-print') }}" style="text-decoration: underline; color: inherit;">Passer au thème Art Print</a>
                @endif
            </span>
        </p>
    </div>
</footer>