{{-- resources/views/articles/hermes-vs-openclaw.blade.php -- Article Vinyl Cult Theme --}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hermes Agent vs OpenClaw : Deux visions de l'agent autonome | Fundisc Labs</title>
    <meta name="description" content="Comparaison technique entre Hermes Agent et OpenClaw. Architecture, mémoire persistante, sécurité et cas d'usage.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/vinyl-cult-theme.css') }}">
    
    <style>
        .article-hero {
            min-height: 70vh;
            display: flex;
            align-items: center;
            background: linear-gradient(180deg, var(--vc-bg-secondary) 0%, var(--vc-bg-primary) 100%);
            border-bottom: 1px solid var(--vc-border);
            position: relative;
            overflow: hidden;
        }
        
        .article-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--vc-label), transparent);
        }
        
        .article-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }
        
        .article-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin: 3rem 0 1.5rem;
            color: var(--vc-text-primary);
            letter-spacing: -0.02em;
        }
        
        .article-content h3 {
            font-size: 1.35rem;
            font-weight: 600;
            margin: 2.5rem 0 1rem;
            color: var(--vc-label);
        }
        
        .article-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--vc-text-secondary);
            margin-bottom: 1.5rem;
        }
        
        .article-content strong {
            color: var(--vc-text-primary);
            font-weight: 600;
        }
        
        .article-content blockquote {
            border-left: 3px solid var(--vc-label);
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: var(--vc-text-muted);
        }
        
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2.5rem 0;
            font-size: 0.95rem;
        }
        
        .comparison-table th {
            background: var(--vc-bg-elevated);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--vc-label);
            border-bottom: 1px solid var(--vc-border);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.8rem;
        }
        
        .comparison-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--vc-border);
            color: var(--vc-text-secondary);
        }
        
        .comparison-table tr:hover td {
            background: rgba(255, 184, 0, 0.03);
        }
        
        .comparison-table td:first-child {
            font-weight: 500;
            color: var(--vc-text-primary);
        }
        
        .highlight-box {
            background: var(--vc-bg-elevated);
            border: 1px solid var(--vc-border);
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 2px;
        }
        
        .highlight-box h4 {
            color: var(--vc-label);
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .highlight-box ul {
            list-style: none;
            padding: 0;
        }
        
        .highlight-box li {
            padding: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
            color: var(--vc-text-secondary);
        }
        
        .highlight-box li::before {
            content: '›';
            position: absolute;
            left: 0;
            color: var(--vc-label);
        }
        
        .author-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--vc-border);
        }
        
        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--vc-bg-elevated);
            border: 2px solid var(--vc-label);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .article-meta {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--vc-text-muted);
        }
        
        .article-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            background: rgba(255, 184, 0, 0.1);
            border: 1px solid var(--vc-label);
            color: var(--vc-label);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .source-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            background: var(--vc-bg-elevated);
            border: 1px solid var(--vc-border);
            color: var(--vc-text-secondary);
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .source-link:hover {
            border-color: var(--vc-label);
            color: var(--vc-label);
        }
    </style>
</head>
<body class="vc-theme">
    
    <!-- Navigation -->
    <nav class="vc-nav">
        <div class="vc-nav-container">
            <a href="{{ route('landing') }}" class="vc-nav-brand">
                <span>Fundisc</span>
            </a>
            
            <ul class="vc-nav-menu">
                <li><a href="{{ route('landing') }}" style="color: var(--vc-label);">← Retour</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Article Hero -->
    <section class="article-hero">
        <div class="vc-container" style="position: relative; z-index: 1;">
            <div style="max-width: 900px; margin: 0 auto;">
                <div class="article-meta">
                    <span class="article-tag">Agents IA</span>
                    <span class="article-tag">Comparatif</span>
                    <span>•</span>
                    <span>27 mars 2026</span>
                    <span>•</span>
                    <span>6 min de lecture</span>
                </div>
                
                <h1 style="font-size: clamp(2rem, 6vw, 3.5rem); font-weight: 800; line-height: 1.1; margin: 2rem 0 1.5rem; letter-spacing: -0.03em;">
                    Hermes Agent vs <span style="color: var(--vc-label);">OpenClaw</span><br>
                    Deux visions de l'agent autonome
                </h1>
                
                <p style="font-size: 1.25rem; color: var(--vc-text-secondary); max-width: 700px; line-height: 1.6;">
                    Hermes Agent apprend et s'améliore seul. OpenClaw orchestre des agents en messagerie. 
                    Analyse comparative des architectures, mémoire, sécurité et cas d'usage.
                </p>
                
                <div class="author-box">
                    <div class="author-avatar">👤</div>
                    <div>
                        <div style="font-weight: 600; color: var(--vc-text-primary);">Louis-Clément Schiltz</div>
                        <div style="font-size: 0.85rem; color: var(--vc-text-muted);">CEO & Founder, Webotit.ai</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Article Content -->
    <article class="article-content">
        
        <!-- En bref -->
        <div class="highlight-box" style="border-color: var(--vc-label);">
            <h4>🎯 En bref</h4>
            <p style="margin: 0; font-size: 1.05rem;">
                <strong style="color: var(--vc-text-primary);">Hermes Agent</strong> et <strong style="color: var(--vc-text-primary);">OpenClaw</strong> adressent des problèmes différents. 
                Hermes Agent, créé par NousResearch, est un agent personnel qui apprend de ses interactions, 
                génère ses propres <em>skills</em> et construit un modèle persistant de l'utilisateur. 
                OpenClaw, créé par Peter Steinberger, est un gateway self-hosted qui orchestre des agents 
                via WhatsApp, Telegram, Discord et iMessage. L'un apprend, l'autre distribue.
            </p>
        </div>
        
        <h2>Deux projets, deux philosophies</h2>
        
        <p>
            OpenClaw et Hermes Agent sont tous les deux open source, tous les deux capables de tourner 
            sur un serveur personnel, et tous les deux connectés à des messageries. Mais la ressemblance s'arrête là.
        </p>
        
        <p>
            <strong>OpenClaw</strong> est un <strong>gateway</strong>. Il centralise les connexions aux canaux de messagerie, 
            gère les sessions, le routage et les permissions, et distribue les messages aux agents qu'on lui branche. 
            Il a accumulé environ <strong>250 000 étoiles GitHub</strong> — une traction massive qui reflète la demande 
            pour un pont self-hosted entre IA et messagerie.
        </p>
        
        <p>
            <strong>Hermes Agent</strong> est un <strong>agent qui apprend</strong>. Créé par NousResearch 
            (l'équipe de Teknium, derrière les modèles Hermes), il ne se contente pas d'exécuter des tâches : 
            il crée ses propres <em>skills</em> à partir de l'expérience, les améliore en les utilisant, 
            et construit une mémoire persistante de l'utilisateur. Avec environ <strong>15 000 étoiles GitHub</strong> 
            et une croissance rapide (216 PRs fusionnées de 63 contributeurs en deux semaines pour la v0.2.0), 
            le projet a trouvé son public.
        </p>
        
        <blockquote>
            La métaphore la plus juste : OpenClaw est le <strong>standard téléphonique</strong>. 
            Hermes Agent est l'<strong>employé senior</strong> qui décroche.
        </blockquote>
        
        <h2>Architecture comparée</h2>
        
        <h3>OpenClaw : Node.js, local-first, gateway-centric</h3>
        
        <p>
            L'architecture d'OpenClaw repose sur un <strong>processus Gateway unique</strong>. 
            Tous les clients se connectent en WebSocket, déclarent leur rôle et leurs scopes au handshake, 
            et les méthodes avec effets de bord exigent des <em>idempotency keys</em>.
        </p>
        
        <div class="highlight-box">
            <h4>🔧 Briques clés OpenClaw</h4>
            <ul>
                <li><strong>Rôles</strong> : operator et node, avec des scopes explicites</li>
                <li><strong>Tokens de device</strong> pour l'authentification persistante</li>
                <li><strong>Approbations d'exécution</strong> pour les actions risquées</li>
                <li><strong>Protocole versionné</strong> pour la compatibilité ascendante</li>
            </ul>
        </div>
        
        <p>
            OpenClaw supporte <strong>13 plateformes de messagerie</strong> : 
            Telegram, WhatsApp, Slack, Discord, Signal, iMessage, email, et d'autres.
        </p>
        
        <h3>Hermes Agent : Python, serveur, apprentissage</h3>
        
        <p>
            Hermes Agent est écrit en <strong>Python (92% du code)</strong> et tourne comme un service systemd 
            sur un VPS ou en local. Le stockage vit dans <code>~/.hermes/</code> : 
            configuration YAML, mémoire en Markdown, sessions en SQLite avec recherche full-text FTS5.
        </p>
        
        <div class="highlight-box">
            <h4>🔧 Briques clés Hermes Agent</h4>
            <ul>
                <li><strong>5 backends</strong> : local, Docker, SSH, Singularity, Modal</li>
                <li><strong>40+ outils intégrés</strong> : terminal, navigateur, vision, TTS, recherche web, exécution de code, subagents</li>
                <li><strong>Support multi-provider</strong> : Nous Portal, OpenRouter, OpenAI, Anthropic, ou tout endpoint compatible</li>
                <li><strong>MCP natif</strong> pour l'extensibilité via des serveurs MCP externes</li>
            </ul>
        </div>
        
        <h2>La mémoire : le vrai différenciateur</h2>
        
        <p>
            C'est sur la mémoire que la différence est la plus marquée.
        </p>
        
        <h3>OpenClaw : sessions isolées</h3>
        
        <p>
            OpenClaw gère les sessions par canal et par expéditeur. 
            La documentation recommande d'activer <code>session.dmScope="per-channel-peer"</code> 
            pour éviter les fuites de contexte entre utilisateurs. 
            C'est une gestion de sessions classique : utile, mais statique.
        </p>
        
        <h3>Hermes Agent : mémoire à deux couches</h3>
        
        <p>
            Hermes Agent implémente un <strong>système de mémoire persistante en deux couches</strong> :
        </p>
        
        <div class="highlight-box">
            <h4>Couche 1 — Toujours en contexte (~1 300 tokens)</h4>
            <ul>
                <li><strong>MEMORY.md</strong> (~800 tokens) : notes de l'agent, conventions apprises, skills créés</li>
                <li><strong>USER.md</strong> (~500 tokens) : profil de l'utilisateur, préférences, projets en cours</li>
                <li>Injection automatique dans le system prompt. Consolidation à 80% de capacité</li>
            </ul>
        </div>
        
        <div class="highlight-box">
            <h4>Couche 2 — Recherche à la demande</h4>
            <ul>
                <li>Toutes les sessions stockées en SQLite avec recherche full-text</li>
                <li>Résumé LLM à la demande pour retrouver du contexte ancien</li>
            </ul>
        </div>
        
        <p>
            <strong>La conséquence</strong> : après quelques semaines d'usage, Hermes connaît vos conventions, 
            votre style, vos projets. Il ne repart pas de zéro à chaque conversation.
        </p>
        
        <h2>Routage intelligent des modèles</h2>
        
        <p>
            Un autre point fort de Hermes Agent : le <strong>routage automatique entre modèles</strong>. 
            L'agent bascule entre un modèle puissant (Claude Opus, GPT-4o) pour les requêtes complexes 
            et un modèle rapide (Mistral Small, GPT-4o-mini) pour les tâches simples.
        </p>
        
        <p>
            OpenClaw ne fait pas de routage de modèles natif. Vous choisissez le modèle au niveau de la configuration, 
            et il reste le même pour toutes les interactions. Pour les cas d'usage à volume élevé, 
            la différence de coût est significative.
        </p>
        
        <h2>Tableau comparatif</h2>
        
        <table class="comparison-table">
            <thead>
                <tr>
                    <th>Critère</th>
                    <th>Hermes Agent</th>
                    <th>OpenClaw</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Métaphore</td>
                    <td>L'employé senior qui apprend</td>
                    <td>Le standard téléphonique intelligent</td>
                </tr>
                <tr>
                    <td>Architecture</td>
                    <td>Python, serveur/VPS</td>
                    <td>Node.js, local-first</td>
                </tr>
                <tr>
                    <td>Apprentissage</td>
                    <td>Boucle fermée (skills auto-générés)</td>
                    <td>Pas d'apprentissage natif</td>
                </tr>
                <tr>
                    <td>Mémoire</td>
                    <td>Dual-layer persistante + compression</td>
                    <td>Sessions isolées par canal</td>
                </tr>
                <tr>
                    <td>Multi-modèles</td>
                    <td>Routage intelligent automatique</td>
                    <td>Modèle unique par config</td>
                </tr>
                <tr>
                    <td>Subagents</td>
                    <td>Oui, avec isolation de contexte</td>
                    <td>Multi-agents via gateway</td>
                </tr>
                <tr>
                    <td>Messagerie</td>
                    <td>Telegram, Discord, Slack, WhatsApp, CLI</td>
                    <td>13 plateformes dont Signal et iMessage</td>
                </tr>
                <tr>
                    <td>GitHub Stars</td>
                    <td>~15 000</td>
                    <td>~250 000</td>
                </tr>
                <tr>
                    <td>Licence</td>
                    <td>Open source</td>
                    <td>Open source</td>
                </tr>
                <tr>
                    <td>Prix d'hébergement</td>
                    <td>VPS à 5 $/mois suffit</td>
                    <td>Laptop ou serveur local</td>
                </tr>
            </tbody>
        </table>
        
        <h2>Quel outil choisir selon votre besoin</h2>
        
        <div class="highlight-box">
            <h4>✅ Choisissez Hermes Agent si :</h4>
            <ul>
                <li>Vous voulez un agent qui apprend vos conventions et s'améliore avec le temps</li>
                <li>Vous avez besoin de compression de contexte pour des conversations longues</li>
                <li>Vous voulez router automatiquement entre modèles cher et pas cher</li>
                <li>Votre cas d'usage est centré sur un seul utilisateur (assistant personnel, dev, recherche)</li>
                <li>Vous voulez déléguer des sous-tâches à des subagents isolés</li>
            </ul>
        </div>
        
        <div class="highlight-box">
            <h4>✅ Choisissez OpenClaw si :</h4>
            <ul>
                <li>Vous voulez un gateway multi-canal pour distribuer des agents à des utilisateurs</li>
                <li>Vous avez besoin d'un control plane centralisé avec permissions et audit</li>
                <li>Votre cas d'usage est multi-utilisateurs (équipe interne, support client)</li>
                <li>Vous voulez connecter plusieurs agents spécialisés derrière un même point d'entrée</li>
                <li>La couverture des plateformes de messagerie (13 canaux) est critique</li>
            </ul>
        </div>
        
        <h2>Les deux ensemble</h2>
        
        <p>
            Ce n'est pas un choix exclusif. <strong>Hermes Agent peut tourner derrière un gateway OpenClaw</strong>. 
            OpenClaw distribue les messages, Hermes Agent les traite avec sa mémoire et ses skills. 
            C'est l'architecture la plus puissante pour des cas d'usage entreprise.
        </p>
        
        <blockquote>
            Chez Webotit, nos agents IA pour l'assurance et la santé combinent déjà ces deux logiques : 
            mémoire contextuelle pour chaque adhérent et distribution multi-canal (web, WhatsApp, téléphone). 
            Les projets open source valident que cette architecture est la bonne.
        </blockquote>
        
        <!-- Source -->
        <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--vc-border);">
            <h4 style="color: var(--vc-text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">
                Source originale
            </h4>
            <a href="https://www.webotit.ai/blog/agents-ia/comparatif/hermes-agent-vs-openclaw-agents-autonomes-comparatif" 
               target="_blank" 
               class="source-link">
               🔗 Lire l'article original sur Webotit.ai
            </a>
            <p style="font-size: 0.8rem; color: var(--vc-text-muted); margin-top: 1rem;">
                Publié le 27 mars 2026 par Louis-Clément Schiltz, CEO & Founder de Webotit.ai
            </p>
        </div>
        
    </article>
    
    <!-- Footer -->
    <footer style="background: var(--vc-bg-secondary); border-top: 1px solid var(--vc-border); padding: 3rem 0;">
        <div class="vc-container" style="text-align: center;">
            <p style="color: var(--vc-text-muted); font-size: 0.9rem;">
                Article repris avec permission • <a href="{{ route('landing') }}" style="color: var(--vc-label);">Fundisc Labs</a>
            </p>
        </div>
    </footer>
    
</body>
</html>
