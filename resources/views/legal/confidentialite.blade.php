@extends('layouts.art-print')

@section('title', 'Politique de Confidentialité')
@section('meta_description', 'Politique de confidentialité de FUN DISC - Comment nous protégeons vos données personnelles.')

@section('content')
<div class="ap-container" style="max-width: 800px; margin: 0 auto; padding: 3rem 1.5rem;">
    
    <nav aria-label="Fil d'Ariane" style="margin-bottom: 2rem;">
        <ol style="list-style: none; padding: 0; margin: 0; display: flex; gap: 0.5rem; color: #999; font-size: 0.875rem;">
            <li><a href="{{ route('landing') }}" style="color: #666; text-decoration: none;">Accueil</a></li>
            <li>/</li>
            <li>Politique de confidentialité</li>
        </ol>
    </nav>

    <h1 style="font-size: 2rem; font-weight: 300; margin-bottom: 2rem; letter-spacing: -0.02em;">Politique de Confidentialité</h1>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">1. Introduction</h2>
        <p style="color: #666; line-height: 1.7;">
            FUN DISC s'engage à protéger la vie privée des utilisateurs de son site. 
            Cette politique de confidentialité explique comment nous collectons, utilisons 
            et protégeons vos données personnelles.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">2. Responsable du traitement</h2>
        <p style="color: #666; line-height: 1.7;">
            Le responsable du traitement des données est : FUN DISC
        </p>
        <p style="color: #666; line-height: 1.7; margin-top: 1rem;">
            Contact : <a href="mailto:contact@fundisc.fr" style="color: #1a1a1a;">contact@fundisc.fr</a>
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">3. Données collectées</h2>
        <p style="color: #666; line-height: 1.7;">Nous collectons les données suivantes :</p>
        <ul style="color: #666; line-height: 1.7; margin-top: 1rem; padding-left: 1.5rem;">
            <li>Données d'identification (nom, email) lors de la création de compte</li>
            <li>Adresses de livraison et de facturation</li>
            <li>Historique des commandes</li>
            <li>Données de contact pour le service client</li>
            <li>Données de navigation (cookies essentiels)</li>
        </ul>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">4. Finalités du traitement</h2>
        <p style="color: #666; line-height: 1.7;">Vos données sont utilisées pour :</p>
        
        <ul style="color: #666; line-height: 1.7; margin-top: 1rem; padding-left: 1.5rem;">
            <li>Gérer votre compte et vos commandes</li>
            <li>Livrer vos achats</li>
            <li>Vous contacter concernant vos commandes</li>
            <li>Répondre à vos questions (service client)</li>
            <li>Respecter nos obligations légales</li>
        </ul>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">5. Base légale</h2>
        <p style="color: #666; line-height: 1.7;">
            Le traitement de vos données est fondé sur l'exécution du contrat de vente (pour les commandes) 
            et votre consentement (pour les communications marketing).
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">6. Durée de conservation</h2>
        <ul style="color: #666; line-height: 1.7; padding-left: 1.5rem;">
            <li>Données de compte : pendant la durée de l'inscription + 3 ans</li>
            <li>Données de commandes : 10 ans (obligations comptables)</li>
            <li>Messages de contact : 3 ans après le dernier contact</li>
        </ul>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">7. Vos droits</h2>
        <p style="color: #666; line-height: 1.7;">Conformément au RGPD, vous disposez des droits suivants :</p>
        
        <ul style="color: #666; line-height: 1.7; margin-top: 1rem; padding-left: 1.5rem;">
            <li>Droit d'accès à vos données</li>
            <li>Droit de rectification</li>
            <li>Droit à l'effacement (« droit à l'oubli »)</li>
            <li>Droit d'opposition</li>
            <li>Droit à la portabilité</li>
        </ul>
        
        <p style="color: #666; line-height: 1.7; margin-top: 1rem;">
            Pour exercer ces droits : <a href="mailto:contact@fundisc.fr" style="color: #1a1a1a;">contact@fundisc.fr</a>
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">8. Cookies</h2>
        <p style="color: #666; line-height: 1.7;">
            Notre site utilise uniquement des cookies essentiels au fonctionnement 
            de la boutique en ligne et à la sécurité des transactions. 
            Aucun cookie de suivi ou de marketing n'est utilisé.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">9. Sécurité</h2>
        <p style="color: #666; line-height: 1.7;">
            Nous mettons en œuvre des mesures techniques et organisationnelles pour protéger 
            vos données : chiffrement SSL, accès restreints, sauvegardes régulières.
        </p>
    </section>

    <section style="margin-bottom: 3rem;">
        <h2 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">10. Contact</h2>
        <p style="color: #666; line-height: 1.7;">
            Pour toute question relative à cette politique : 
            <a href="mailto:contact@fundisc.fr" style="color: #1a1a1a;">contact@fundisc.fr</a>
        </p>
        
        <p style="color: #666; line-height: 1.7; margin-top: 1rem;">
            Vous pouvez également contacter la CNIL si vous estimez que vos droits ne sont pas respectés.
        </p>
    </section>

    <div style="color: #999; font-size: 0.85rem; margin-top: 4rem; padding-top: 2rem; border-top: 1px solid #eee;">
        Dernière mise à jour : 2026
    </div>

</div>
@endsection
