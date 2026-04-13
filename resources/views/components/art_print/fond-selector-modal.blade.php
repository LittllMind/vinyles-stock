{{-- resources/views/components/art_print/fond-selector-modal.blade.php --}}
{{-- Modale de sélection de fond - ART PRINT --}}

@php
// Prix des fonds (à configurer ou récupérer depuis la DB)
$fonds = [
    ['id' => 'standard', 'name' => 'Sans fond', 'description' => 'Vinyle nu', 'image' => asset('images/fonds/standard.jpg'), 'price' => 0, 'class' => 'fond-standard'],
    ['id' => 'miroir', 'name' => 'Miroir Argenté', 'description' => 'Laser Disc Argenté', 'image' => asset('images/fonds/miroir.jpg'), 'price' => 8, 'class' => 'fond-miroir'],
    ['id' => 'dore', 'name' => 'Doré', 'description' => 'Laser Disc Doré', 'image' => asset('images/fonds/dore.jpg'), 'price' => 13, 'class' => 'fond-dore'],
];
@endphp

<div id="fondModal" class="fond-modal hidden">
    <div class="modal-backdrop" onclick="closeFondModal()"></div>
    
    <div class="modal-content">
        <button class="modal-close" onclick="closeFondModal()" type="button">
            ×
        </button>
        
        <div class="modal-header">
            <h3 class="modal-title">Choisir votre finition</h3>
            <p class="modal-subtitle">Sélectionnez le fond pour votre vinyle</p>
        </div>
        
        <form id="fondForm" action="{{ route('cart.add') }}" method="POST" class="fond-options">
            @csrf
            <input type="hidden" name="vinyle_id" id="modalVinyleId">
            <input type="hidden" name="quantite" value="1">
            
            <div class="fond-grid">
                @foreach($fonds as $fond)
                <label class="fond-option" data-fond="{{ $fond['id'] }}">
                    <input type="radio" name="fond_id" value="{{ $fond['id'] }}" 
                           {{ $fond['id'] === 'standard' ? 'checked' : '' }}
                           class="fond-radio">
                    
                    <div class="fond-card">
                        <div class="fond-preview {{ $fond['class'] }}">
                            <div class="vinyle-mockup">
                                <div class="vinyle-disc" data-fond="{{ $fond['id'] }}">
                                    <div class="disc-center"></div>
                                    <div class="disc-label"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="fond-info">
                            <h4 class="fond-name">{{ $fond['name'] }}</h4>
                            <p class="fond-desc">{{ $fond['description'] }}</p>
                            <div class="fond-price">
                                @if($fond['price'] > 0)
                                    <span class="price-badge">+ {{ $fond['price'] }} €</span>
                                @else
                                    <span class="price-free">Inclus</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="fond-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 6L9 17l-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            
            <div class="modal-footer">
                <div class="price-summary">
                    <span>Total:</span>
                    <span class="total-price" id="modalTotalPrice">-- €</span>
                </div>
                
                <button type="submit" class="btn-add-cart">
                    <span>Ajouter au panier</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 6h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 10a4 4 0 01-8 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Modale */
.fond-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.fond-modal.hidden {
    display: none !important;
}

.modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 720px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    padding: 2rem;
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    border: none;
    background: #f5f5f5;
    border-radius: 50%;
    font-size: 1.5rem;
    line-height: 1;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #e5e5e5;
    color: #1a1a1a;
}

.modal-header {
    text-align: center;
    margin-bottom: 2rem;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 300;
    color: #1a1a1a;
    margin: 0 0 0.5rem;
}

.modal-subtitle {
    font-size: 0.95rem;
    color: #999;
    margin: 0;
}

/* Grille des fonds */
.fond-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (max-width: 640px) {
    .fond-grid {
        grid-template-columns: 1fr;
    }
}

/* Option fond */
.fond-option {
    cursor: pointer;
}

.fond-radio {
    display: none;
}

.fond-card {
    border: 2px solid #e5e5e5;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.2s;
    position: relative;
}

.fond-option:hover .fond-card {
    border-color: #999;
}

.fond-radio:checked + .fond-card {
    border-color: #1a1a1a;
    background: #fafafa;
}

.fond-radio:checked + .fond-card .fond-check {
    opacity: 1;
    transform: scale(1);
}

/* Preview vinyle */
.fond-preview {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin-bottom: 1rem;
    position: relative;
}

.fond-preview.fond-standard {
    background: #f5f5f5;
}

.fond-preview.fond-miroir {
    background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 50%, #e0e0e0 100%);
}

.fond-preview.fond-dore {
    background: linear-gradient(135deg, #f5d547 0%, #e6c200 50%, #d4a520 100%);
}

.vinyle-mockup {
    width: 80px;
    height: 80px;
}

.vinyle-disc {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #1a1a1a;
    position: relative;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.vinyle-disc[data-fond="standard"] {
    background: #1a1a1a;
}

.vinyle-disc[data-fond="miroir"] {
    background: linear-gradient(135deg, #c0c0c0 0%, #a0a0a0 50%, #808080 100%);
}

.vinyle-disc[data-fond="dore"] {
    background: linear-gradient(135deg, #FFD700 0%, #DAA520 50%, #B8860B 100%);
}

.disc-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 25%;
    height: 25%;
    background: #333;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.2);
}

.disc-label {
    position: absolute;
    top: 20%;
    left: 20%;
    right: 20%;
    height: 30%;
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
}

/* Info fond */
.fond-name {
    font-size: 1rem;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0 0 0.25rem;
}

.fond-desc {
    font-size: 0.8rem;
    color: #999;
    margin: 0;
}

.fond-price {
    margin-top: 0.75rem;
}

.price-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    background: #1a1a1a;
    color: white;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 500;
}

.price-free {
    color: #22c55e;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Check indicator */
.fond-check {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 24px;
    height: 24px;
    background: #1a1a1a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.2s;
}

/* Footer */
.modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e5e5;
}

.price-summary {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    font-size: 1.1rem;
}

.total-price {
    font-size: 1.5rem;
    font-weight: 500;
    color: #1a1a1a;
}

.btn-add-cart {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: #1a1a1a;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-add-cart:hover {
    background: #333;
    transform: translateY(-1px);
}
</style>

<script>
delete window.fondData;

// Données injectées par le serveur
const fondData = {
    fonds: @json($fonds ?? []),
    vinyles: {} // Sera populé dynamiquement
};

window.fondData = fondData;

// Ouvrir la modale avec un vinyle spécifique
function openFondModal(vinyleId, vinylePrice, vinyleName) {
    const modal = document.getElementById('fondModal');
    const form = document.getElementById('fondForm');
    
    document.getElementById('modalVinyleId').value = vinyleId;
    
    // Stocker le prix de base
    fondData.vinyles[vinyleId] = {
        basePrice: parseFloat(vinylePrice),
        name: vinyleName
    };
    
    updateTotalPrice();
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Fermer la modale
function closeFondModal() {
    const modal = document.getElementById('fondModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

// Mettre à jour le prix total
function updateTotalPrice() {
    const vinyleId = document.getElementById('modalVinyleId').value;
    const selectedFond = document.querySelector('input[name="fond_id"]:checked');
    
    if (!vinyleId || !selectedFond || !fondData.vinyles[vinyleId]) return;
    
    const basePrice = fondData.vinyles[vinyleId].basePrice;
    const fondOption = fondData.fonds.find(f => f.id === selectedFond.value);
    const fondPrice = fondOption ? fondOption.price : 0;
    
    const total = basePrice + fondPrice;
    
    document.getElementById('modalTotalPrice').textContent = total.toLocaleString('fr-FR', {
        style: 'decimal',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }) + ' €';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const fondRadios = document.querySelectorAll('input[name="fond_id"]');
    
    fondRadios.forEach(radio => {
        radio.addEventListener('change', updateTotalPrice);
    });
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFondModal();
        }
    });
});

// Exposer les fonctions globalement
window.openFondModal = openFondModal;
window.closeFondModal = closeFondModal;
</script>