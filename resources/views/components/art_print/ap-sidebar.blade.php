{{-- resources/views/components/art_print/ap-sidebar.blade.php --}}
{{-- Sidebar Admin - Navigation gestion ergonomique --}}

@php
$currentRoute = request()->route() ? request()->route()->getName() : '';

$menuItems = [
    ['route' => 'vinyles.index', 'label' => 'Vinyles', 'icon' => '💿'],
    ['route' => 'admin.orders.index', 'label' => 'Commandes', 'icon' => '📦'],
    ['route' => 'mouvements.index', 'label' => 'Mouvements', 'icon' => '📊'],
];

$menuItemsBottom = [
    ['route' => 'fonds.index', 'label' => 'Fonds', 'icon' => '🖼️'],
    ['route' => 'ventes.index', 'label' => 'Ventes', 'icon' => '💰'],
    ['route' => 'stats', 'label' => 'Statistiques', 'icon' => '📈'],
];

function isActive($route) {
    return request()->routeIs($route . '*');
}
@endphp

<aside class="ap-sidebar">
    <div class="sidebar-header">
        <a href="{{ route('landing') }}" class="sidebar-brand">
            <span class="brand-icon">◉</span>
            <span class="brand-text">FUN DISC</span>
        </a>
        <p class="sidebar-subtitle">Admin</p>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            @foreach($menuItems as $item)
                <a href="{{ route($item['route']) }}" 
                   class="nav-item {{ isActive($item['route']) ? 'active' : '' }}">
                    <span class="nav-icon">{{ $item['icon'] }}</span>
                    <span class="nav-label">{{ $item['label'] }}</span>
                    @if(isActive($item['route']))
                        <span class="nav-indicator"></span>
                    @endif
                </a>
            @endforeach
        </div>
        
        <div class="nav-divider"></div>
        
        <div class="nav-section">
            @foreach($menuItemsBottom as $item)
                <a href="{{ route($item['route']) }}" 
                   class="nav-item {{ isActive($item['route']) ? 'active' : '' }}">
                    <span class="nav-icon">{{ $item['icon'] }}</span>
                    <span class="nav-label">{{ $item['label'] }}</span>
                    @if(isActive($item['route']))
                        <span class="nav-indicator"></span>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>
    
    <div class="sidebar-footer">
        <a href="{{ route('marche.index') }}" class="mode-marche-btn">
            <span class="btn-icon">🛒</span>
            <span class="btn-text">Mode Marché</span>
        </a>
        
        @if(Auth::check())
            <div class="user-mini">
                <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>
        @endif
    </div>
</aside>

<style>
.ap-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: 240px;
    background: #FAFAFA;
    border-right: 1px solid #E5E5E5;
    display: flex;
    flex-direction: column;
    z-index: 100;
}

.sidebar-header {
    padding: 2rem 1.5rem 1.5rem;
    border-bottom: 1px solid #E5E5E5;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    color: #1A1A1A;
    margin-bottom: 0.25rem;
}

.brand-icon {
    font-size: 1.25rem;
    color: #666;
}

.brand-text {
    font-size: 1rem;
    font-weight: 500;
    letter-spacing: 0.1em;
}

.sidebar-subtitle {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #999;
    margin: 0;
    padding-left: 2rem;
}

.sidebar-nav {
    flex: 1;
    padding: 1.5rem 0;
    overflow-y: auto;
}

.nav-section {
    padding: 0 0.75rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1rem;
    margin-bottom: 0.25rem;
    border-radius: 8px;
    text-decoration: none;
    color: #666;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    position: relative;
}

.nav-item:hover {
    background: #F0F0F0;
    color: #1A1A1A;
}

.nav-item.active {
    background: #1A1A1A;
    color: white;
}

.nav-icon {
    font-size: 1.1rem;
    width: 24px;
    text-align: center;
}

.nav-label {
    flex: 1;
}

.nav-indicator {
    width: 6px;
    height: 6px;
    background: #FFB800;
    border-radius: 50%;
}

.nav-divider {
    height: 1px;
    background: #E5E5E5;
    margin: 1rem 0.75rem;
}

.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid #E5E5E5;
    background: #F5F5F5;
}

.mode-marche-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem;
    background: linear-gradient(135deg, #FFB800 0%, #FF8C00 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.mode-marche-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 184, 0, 0.3);
}

.user-mini {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #E5E5E5;
}

.user-avatar {
    width: 32px;
    height: 32px;
    background: #1A1A1A;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 600;
}

.user-info {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.user-name {
    font-size: 0.85rem;
    font-weight: 500;
    color: #1A1A1A;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.7rem;
    color: #999;
    text-transform: capitalize;
}

/* Responsive: cacher sidebar sur mobile */
@media (max-width: 1024px) {
    .ap-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .ap-sidebar.open {
        transform: translateX(0);
    }
}
</style>