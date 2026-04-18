@extends('layouts.art-print')

@section('title', 'Conditions Générales de Vente')
@section('meta_description', 'Conditions générales de vente de FUN DISC - Ventes de vinyles découpés en œuvres d\'art.')

@section('content')
<div class="ap-container" style="max-width: 800px; margin: 0 auto; padding: 3rem 1.5rem;">
    
    <nav aria-label="Fil d'Ariane" style="margin-bottom: 2rem;">
        <ol style="list-style: none; padding: 0; margin: 0; display: flex; gap: 0.5rem; color: #999; font-size: 0.875rem;">
            <li><a href="{{ route('landing') }}" style="color: #666; text-decoration: none;">Accueil</a></li>
            <li>/</li>
            <li>Conditions de vente</li>
        </ol>
    </nav>

    <h1 style="font-size: 2rem; font-weight: 300; margin-bottom: 2rem; letter-spacing: -0.02em;">Conditions Générales de Vente</h1>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">1. Préambule</h2>
        <p style="color: #666; line-height: 1.7;">
            Les présentes Conditions Générales de Vente (CGV) régissent les relations commerciales 
            entre FUN DISC et tout client effectuant un achat sur le site fundisc.fr.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">2. Produits</h2>
        <p style="color: #666; line-height: 1.7;">
            FUN DISC propose des vinyles découpés, transformés en œuvres d'art uniques. 
            Chaque pièce est sélectionnée pour son potentiel artistique et son histoire musicale.
        </p>
        <p style="color: #666; line-height: 1.7; margin-top: 1rem;">
            Les photographies des produits sont fournies à titre indicatif. Chaque œuvre étant unique, 
            des variations peuvent exister par rapport aux visuels présentés.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">3. Prix</h2>
        <p style="color: #666; line-height: 1.7;">
            Les prix sont indiqués en euros TTC. Les frais de livraison sont ajoutés au prix des produits 
            selon le mode de livraison choisi. Le montant total, incluant les frais de livraison, 
            est indiqué avant validation finale de la commande.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">4. Commande</h2>
        <p style="color: #666; line-height: 1.7;">
            La commande est validée après création d'un compte client et paiement sécurisé.
            Un email de confirmation récapitulant la commande est envoyé au client.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">5. Paiement</h2>
        <p style="color: #666; line-height: 1.7;">
            Le paiement s'effectue par carte bancaire via Stripe, plateforme sécurisée. 
            La commande n'est confirmée qu'après validation du paiement.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">6. Livraison</h2>
        <p style="color: #666; line-height: 1.7;">
            Les produits sont expédiés à l'adresse indiquée par le client lors de la commande. 
            Les délais de livraison sont de 3 à 7 jours ouvrés en France métropolitaine.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">7. Droit de rétractation</h2>
        <p style="color: #666; line-height: 1.7;">
            Conformément à l'article L221-18 du Code de la consommation, le client dispose d'un délai 
            de 14 jours calendaires pour exercer son droit de rétractation sans avoir à justifier de motifs.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">8. Service client</h2>
        <p style="color: #666; line-height: 1.7;">
            Pour toute question : <a href="mailto:contact@fundisc.fr" style="color: #1a1a1a;">contact@fundisc.fr</a>
        </p>
    </section>

    <div style="color: #999; font-size: 0.85rem; margin-top: 4rem; padding-top: 2rem; border-top: 1px solid #eee;">
        Dernière mise à jour : 2026
    </div>

</div>
@endsection
