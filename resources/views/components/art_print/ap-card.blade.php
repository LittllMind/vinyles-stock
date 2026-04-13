{{-- resources/views/components/art_print/ap-card.blade.php --}}
{{-- Carte œuvre/vinyle style galerie --}}

@props(['title' => '', 'year' => '', 'subtitle' => '', 'price' => '', 'image' => '', 'url' => '#', 'soldOut' => false, 'addToCartUrl' => null, 'id' => null])

<article class="ap-card" style="cursor: pointer;" onclick="if(event.target.tagName !== 'BUTTON' && event.target.tagName !== 'A') { window.location.href='{{ $url }}'; }">
    
    {{-- Image Œuvre --}}
    <div class="ap-card-image">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <span style="font-size: 4rem;">💿</span>
        @endif
    </div>
    
    {{-- Méta Œuvre --}}
    <div class="ap-card-meta">
        <h3 class="ap-card-title">{{ $title }}</h3>
        @if($year)
            <span class="ap-card-year">{{ $year }}</span>
        @endif
    </div>
    
    @if($subtitle)
        <p class="ap-card-artist">{{ $subtitle }}</p>
    @endif
    
    @if($price || !$soldOut)
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
            @if($price)
                <p class="ap-card-price">€ {{ $price }}</p>
            @endif
            
            @if($addToCartUrl && !$soldOut && $id)
                <form action="{{ $addToCartUrl }}" method="POST" style="margin: 0;" onclick="event.stopPropagation();">
                    @csrf
                    <input type="hidden" name="vinyle_id" value="{{ $id }}">
                    <input type="hidden" name="quantite" value="1">
                    <input type="hidden" name="fond" value="standard">
                    
                    <button type="submit" class="ap-btn ap-btn-dark" style="padding: 0.6rem 1.2rem;">
                        +
                    </button>
                </form>
            @elseif($soldOut)
                <span style="font-size: 0.7rem; color: #999; text-transform: uppercase; letter-spacing: 0.1em;">
                    Épuisé
                </span>
            @endif
        </div>
    @endif
</article>
