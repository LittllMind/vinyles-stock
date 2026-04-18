@extends('layouts.art-print')

@section('title', 'Mentions Légales')
@section('meta_description', 'Mentions légales de FUN DISC - Informations sur l\'entreprise, l\'hébergement et le site fundisc.fr.')

@section('content')
<div class="ap-container" style="max-width: 800px; margin: 0 auto; padding: 3rem 1.5rem;">
    
    <nav aria-label="Fil d'Ariane" style="margin-bottom: 2rem;">
        <ol style="list-style: none; padding: 0; margin: 0; display: flex; gap: 0.5rem; color: #999; font-size: 0.875rem;">
            <li><a href="{{ route('landing') }}" style="color: #666; text-decoration: none;">Accueil</a></li>
            <li>/</li>
            <li>Mentions légales</li>
        </ol>
    </nav>

    <h1 style="font-size: 2rem; font-weight: 300; margin-bottom: 2rem; letter-spacing: -0.02em;">Mentions Légales</h1>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">1. Éditeur du site</h2>
        <div style="color: #666; line-height: 1.7;">
            <p><strong>FUN DISC</strong></p>
            <p>Société en cours d'immatriculation</p>
            <p>Siège social : France</p>
            <p>Email : <a href="mailto:contact@fundisc.fr" style="color: #1a1a1a;">contact@fundisc.fr</a></p>
        </div>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">2. Directeur de la publication</h2>
        <p style="color: #666; line-height: 1.7;">Le directeur de la publication est le fondateur de FUN DISC.</p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">3. Hébergement</h2>
        <div style="color: #666; line-height: 1.7;">
            <p>Ce site est hébergé par : <strong>Hostinger International Ltd</strong></p>
            <p>Adresse : 61 Lordou Vironos Street, 6023 Larnaca, Chypre</p>
            <p>Site web : <a href="https://www.hostinger.fr" target="_blank" rel="noopener" style="color: #1a1a1a;">www.hostinger.fr</a></p>
        </div>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">4. Propriété intellectuelle</h2>
        <p style="color: #666; line-height: 1.7;">
            L'ensemble des éléments constituant ce site (textes, photographies, illustrations, logos, 
            icônes, mise en page, base de données) sont la propriété exclusive de FUN DISC 
            ou de ses partenaires.
        </p>
        <p style="color: #666; line-height: 1.7; margin-top: 1rem;">
            Toute reproduction, représentation, modification, publication, transmission, 
            ou adaptation, totale ou partielle, des éléments du site est strictement interdite 
            sans l'autorisation écrite préalable de FUN DISC.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">5. Données personnelles</h2>
        <p style="color: #666; line-height: 1.7;">
            Les informations personnelles collectées sur ce site sont traitées conformément 
            à notre <a href="{{ route('confidentialite') }}" style="color: #1a1a1a;">Politique de confidentialité</a>.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">6. Cookies</h2>
        <p style="color: #666; line-height: 1.7;">
            Ce site utilise des cookies essentiels au fonctionnement du site et à la sécurité 
            des transactions. Pour en savoir plus, consultez notre politique de confidentialité.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">7. Contact</h2>
        <p style="color: #666; line-height: 1.7;">
            Pour toute question concernant ces mentions légales : 
            <a href="mailto:contact@fundisc.fr" style="color: #1a1a1a;">contact@fundisc.fr</a>
        </p>
    </section>

    <div style="color: #999; font-size: 0.85rem; margin-top: 4rem; padding-top: 2rem; border-top: 1px solid #eee;">
        Dernière mise à jour : 2026
    </div>

</div>
@endsection
