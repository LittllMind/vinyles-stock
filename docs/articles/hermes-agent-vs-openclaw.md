# Hermes Agent vs OpenClaw : deux visions de l'agent autonome

**Source:** [Webotit.ai](https://www.webotit.ai/blog/agents-ia/comparatif/hermes-agent-vs-openclaw-agents-autonomes-comparatif)  
**Auteur:** Louis-Clément Schiltz, CEO & Founder, Webotit.ai  
**Date:** 27 mars 2026

---

## En bref

Hermes Agent et OpenClaw adressent des problèmes différents. Hermes Agent, créé par NousResearch, est un agent personnel qui apprend de ses interactions, génère ses propres skills et construit un modèle persistant de l'utilisateur. OpenClaw, créé par Peter Steinberger, est un gateway self-hosted qui orchestre des agents via WhatsApp, Telegram, Discord et iMessage. L'un apprend, l'autre distribue.

---

## Deux projets, deux philosophies

OpenClaw et Hermes Agent sont tous les deux open source, tous les deux capables de tourner sur un serveur personnel, et tous les deux connectés à des messageries. Mais la ressemblance s'arrête là.

**OpenClaw** est un **gateway**. Il centralise les connexions aux canaux de messagerie, gère les sessions, le routage et les permissions, et distribue les messages aux agents qu'on lui branche. Il a accumulé environ 250 000 étoiles GitHub — une traction massive qui reflète la demande pour un pont self-hosted entre IA et messagerie.

**Hermes Agent** est un **agent qui apprend**. Créé par NousResearch (l'équipe de Teknium, derrière les modèles Hermes), il ne se contente pas d'exécuter des tâches : il crée ses propres skills à partir de l'expérience, les améliore en les utilisant, et construit une mémoire persistante de l'utilisateur. Avec environ 15 000 étoiles GitHub et une croissance rapide (216 PRs fusionnées de 63 contributeurs en deux semaines pour la v0.2.0), le projet a trouvé son public.

> La métaphore la plus juste : OpenClaw est le **standard téléphonique**. Hermes Agent est l'**employé senior** qui décroche.

---

## Architecture comparée

### OpenClaw : Node.js, local-first, gateway-centric

L'architecture d'OpenClaw repose sur un processus Gateway unique. Tous les clients se connectent en WebSocket, déclarent leur rôle et leurs scopes au handshake, et les méthodes avec effets de bord exigent des idempotency keys.

**Les briques clés :**
- **Rôles** : `operator` et `node`, avec des scopes explicites
- **Tokens de device** pour l'authentification persistante
- **Approbations d'exécution** pour les actions risquées
- **Protocole versionné** pour la compatibilité ascendante

OpenClaw supporte 13 plateformes de messagerie : Telegram, WhatsApp, Slack, Discord, Signal, iMessage, email, et d'autres.

### Hermes Agent : Python, serveur, apprentissage

Hermes Agent est écrit en Python (92 % du code) et tourne comme un service systemd sur un VPS ou en local. Le stockage vit dans `~/.hermes/` : configuration YAML, mémoire en Markdown, sessions en SQLite avec recherche full-text FTS5.

**Les briques clés :**
- **5 backends** : local, Docker, SSH, Singularity, Modal
- **40+ outils intégrés** : terminal, navigateur, vision, TTS, recherche web, exécution de code, subagents
- **Support multi-provider** : Nous Portal, OpenRouter, OpenAI, Anthropic, ou tout endpoint compatible
- **MCP natif** pour l'extensibilité via des serveurs MCP externes

---

## La mémoire : le vrai différenciateur

### OpenClaw : sessions isolées

OpenClaw gère les sessions par canal et par expéditeur. La documentation recommande d'activer `session.dmScope="per-channel-peer"` pour éviter les fuites de contexte entre utilisateurs. C'est une gestion de sessions classique : utile, mais statique.

### Hermes Agent : mémoire à deux couches

Hermes Agent implémente un système de mémoire persistante en deux couches :

**Couche 1 — toujours en contexte (~1 300 tokens) :**
- `MEMORY.md` (~800 tokens) : notes de l'agent, conventions apprises, skills créés
- `USER.md` (~500 tokens) : profil de l'utilisateur, préférences, projets en cours
- Injection automatique dans le system prompt. Consolidation à 80 % de capacité

**Couche 2 — recherche à la demande :**
- Toutes les sessions stockées en SQLite avec recherche full-text
- Résumé LLM à la demande pour retrouver du contexte ancien

**La conséquence** : après quelques semaines d'usage, Hermes connaît vos conventions, votre style, vos projets. Il ne repart pas de zéro à chaque conversation.

---

## La compression de contexte dual-layer

Hermes Agent intègre aussi un système de compression de contexte en deux passes :

- **Pré-compression gateway** à 85 % d'usage de la fenêtre de contexte
- **Compression agent** à un seuil configurable (par défaut 50 %)

L'algorithme protège les 3 premiers et les 4 derniers tours, résume la section intermédiaire via un modèle auxiliaire. Le résultat : des conversations quasi-infinies sans crash de contexte.

---

## Routage intelligent des modèles

Un autre point fort de Hermes Agent : le routage automatique entre modèles. L'agent bascule entre un modèle puissant (Claude Opus, GPT-4o) pour les requêtes complexes et un modèle rapide (Mistral Small, GPT-4o-mini) pour les tâches simples.

OpenClaw ne fait pas de routage de modèles natif. Vous choisissez le modèle au niveau de la configuration, et il reste le même pour toutes les interactions. Pour les cas d'usage à volume élevé, la différence de coût est significative.

---

## Subagents et isolation

### Hermes Agent : parent-enfant avec isolation

Hermes Agent supporte des subagents avec isolation de contexte. Le parent ne voit que le résumé, jamais le contexte complet des enfants. Cela évite le "context poisoning" — quand le contexte d'une sous-tâche pollue la conversation principale.

Le mode worktree (`hermes -w`) permet aussi de lancer un agent dans un git worktree isolé avec sa propre branche. Plusieurs agents peuvent travailler sur le même repo en parallèle sans conflits.

### OpenClaw : multi-agents via le gateway

OpenClaw orchestre plusieurs agents derrière un même gateway. Chaque agent a son workspace, ses permissions et ses canaux. Le gateway gère le routage entre les agents et les utilisateurs.

**L'approche est différente** : Hermes isole les sous-tâches d'un même agent, OpenClaw isole les agents eux-mêmes.

---

## Sécurité

Les deux projets prennent la sécurité au sérieux, mais avec des angles différents.

**OpenClaw** met l'accent sur le contrôle d'accès :
- Politiques DM (pairing, allowlist, open)
- Sandboxing Docker pour réduire le blast radius
- Scopes explicites au handshake
- Audit CLI (`openclaw security audit --deep`)

**Hermes Agent** met l'accent sur la protection des données :
- Scanning Tirith pré-exécution des commandes
- Redaction PII automatique avant envoi au LLM
- Isolation des variables d'environnement
- Scanning mémoire anti-injection

---

## Tableau comparatif

| Critère | Hermes Agent | OpenClaw |
|---------|--------------|----------|
| Métaphore | L'employé senior qui apprend | Le standard téléphonique intelligent |
| Architecture | Python, serveur/VPS | Node.js, local-first |
| Apprentissage | Boucle fermée (skills auto-générés) | Pas d'apprentissage natif |
| Mémoire | Dual-layer persistante + compression | Sessions isolées par canal |
| Multi-modèles | Routage intelligent automatique | Modèle unique par config |
| Subagents | Oui, avec isolation de contexte | Multi-agents via gateway |
| Messagerie | Telegram, Discord, Slack, WhatsApp, CLI | 13 plateformes dont Signal et iMessage |
| GitHub Stars | ~15 000 | ~250 000 |
| Licence | Open source | Open source |
| Cas d'usage principal | Assistant personnel + dev + recherche | Gateway multi-canal multi-agents |
| Prix d'hébergement | VPS à 5 $/mois suffit | Laptop ou serveur local |

---

## Quel outil choisir selon votre besoin

### Choisissez Hermes Agent si :

- Vous voulez un agent qui apprend vos conventions et s'améliore avec le temps
- Vous avez besoin de compression de contexte pour des conversations longues
- Vous voulez router automatiquement entre modèles cher et pas cher
- Votre cas d'usage est centré sur un seul utilisateur (assistant personnel, dev, recherche)
- Vous voulez déléguer des sous-tâches à des subagents isolés

### Choisissez OpenClaw si :

- Vous voulez un gateway multi-canal pour distribuer des agents à des utilisateurs
- Vous avez besoin d'un control plane centralisé avec permissions et audit
- Votre cas d'usage est multi-utilisateurs (équipe interne, support client)
- Vous voulez connecter plusieurs agents spécialisés derrière un même point d'entrée
- La couverture des plateformes de messagerie (13 canaux) est critique

---

## Les deux ensemble

Ce n'est pas un choix exclusif. **Hermes Agent peut tourner derrière un gateway OpenClaw**. OpenClaw distribue les messages, Hermes Agent les traite avec sa mémoire et ses skills. C'est l'architecture la plus puissante pour des cas d'usage entreprise.

---

## Ce que ça signifie pour la relation client automatisée

Pour les équipes qui déploient des chatbots et des agents en relation client, ces deux projets montrent la direction du marché :

- **La mémoire persistante (Hermes)** est ce qui transforme un bot en assistant utile sur la durée
- **Le gateway multi-canal (OpenClaw)** est ce qui permet de déployer cet assistant là où sont les clients

---

## FAQ

**Q: Hermes Agent fonctionne-t-il avec Claude ou uniquement avec les modèles Hermes de NousResearch ?**

Hermes Agent supporte Claude, GPT-4o, et tout modèle compatible OpenAI API. Le nom vient de NousResearch mais l'agent est provider-agnostic.

**Q: OpenClaw peut-il apprendre comme Hermes Agent ?**

Pas nativement. OpenClaw est un gateway — l'apprentissage doit être implémenté au niveau de l'agent qu'il orchestre.

**Q: Lequel est le plus adapté pour du support client B2B ?**

OpenClaw pour la distribution multi-canal, Hermes Agent pour la mémoire contextuelle. Les deux ensemble pour une solution complète.

**Q: Les deux outils sont-ils utilisables en production ?**

Oui, tous deux sont open source et utilisés en production. OpenClaw a plus de traction (250k stars), Hermes Agent croît vite avec une communauté active.

---

## Sources et références

1. [OpenClaw Docs, accueil](https://openclaw.io)
2. [NousResearch, "Hermes Agent — The agent that grows with you"](https://github.com/NousResearch/hermes-agent)
3. [NousResearch, "Release Hermes Agent v0.2.0"](https://github.com/NousResearch/hermes-agent/releases)
4. [OpenClaw Docs, "Gateway Protocol"](https://docs.openclaw.io)
5. [OpenClaw Docs, "Security"](https://docs.openclaw.io/security)
6. [OpenClaw Docs, "Sandboxing"](https://docs.openclaw.io/sandboxing)
7. [OpenClaw CLI, "security"](https://docs.openclaw.io/cli)
