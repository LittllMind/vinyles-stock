# 🔍 Audit Pré-Production - Vinyles ART PRINT
**Date**: 13/04/2025 - Branche: master (merged T-2.0)

---

## ✅ Ce qui fonctionne

### Données
- [x] 40 vinyles uniques (pas de doublons)
- [x] Prix en centimes (vinyles.prix = integer)
- [x] 3 fonds de présentation (Standard/Miroir/Doré)
- [x] 3 utilisateurs de test créés
- [x] StockService pour la gestion des réservations

### Frontend
- [x] Thème ART PRINT actif (session-based)
- [x] Catalogue avec pagination (24/40 vinyles)
- [x] Page d'accueil avec sélection
- [x] Modale des fonds fonctionnelle
- [x] Panier (ajout, suppression)

### Backend
- [x] Authentification (login/register)
- [x] Gestion du panier
- [x] Création de commandes
- [x] Gestion des stocks (réservation à la commande)
- [x] Webhook Stripe (encapsulé, à tester)

---

## ⚠️ À vérifier/tester avant production

### 1. Processus de paiement complet
- [ ] Paiement Stripe en mode TEST
- [ ] Webhook Stripe reçoit bien les événements
- [ ] Confirmation email envoyée (après paiement réussi)
- [ ] Stock décrémenté après paiement

### 2. Gestion des fonds
- [ ] Sélection du fond dans la modale
- [ ] Prix total calculé (vinyle + fond)
- [ ] Stock des fonds décrémenté après commande
- [ ] Affichage dans le panier du fond choisi

### 3. Commandes
- [ ] Historique des commandes (client)
- [ ] Détail d'une commande
- [ ] Statut de livraison mis à jour

### 4. Admin Dashboard
- [ ] Visualisation des commandes en cours
- [ ] Gestion des stocks (alertes)
- [ ] Rapports de ventes

### 5. Emails
- [ ] Confirmation de commande (client)
- [ ] Notification admin (nouvelle commande)
- [ ] Expédition (quand commande envoyée)
- [ ] Notification client (messagerie interne)

### 6. Responsive/Mobile
- [ ] Test sur smartphone
- [ ] Navigation tactile
- [ ] Modale fonds utilisable

---

## 🚨 Bloquant pour production

1. **Paiement Stripe LIVE**
   - Clés API actuellement en mode TEST
   - Besoin: Clés LIVE + configuration webhook

2. **Emails**
   - Configuration SMTP (Mailtrap pour test, SMTP Hostinger pour prod)
   - Templates à vérifier (logos, liens)

3. **HTTPS/SSL**
   - Certificat SSL sur Hostinger
   - APP_URL en https://

4. **Stock réel**
   - Quantités fictives actuellement
   - Besoin: Inventaire réel saisi

---

## 📋 Prochaines étapes recommandées

### Phase 1: Tests fonctionnels (ce soir)
1. ☐ Parcours complet: Vinyle → Panier → Commande → Paiement (test)
2. ☐ Vérifier emails reçus
3. ☐ Tester modale fonds + prix total
4. ☐ Mobile responsive

### Phase 2: Configuration production
1. ☐ Clés Stripe LIVE
2. ☐ Configuration SMTP (Hostinger)
3. ☐ Certificat SSL
4. ☐ Variables d'environnement

### Phase 3: Déploiement
1. ☐ Migration/seed sur Hostinger
2. ☐ Upload photos vinyles
3. ☐ Tests en production
4. ☐ Ouverture au public

---

## 🔧 Bugs connus/minimes

- Prix affichés avec emoji 💿 (manque photos réelles)
- Pagination basique (possibilité d'améliorer UX)

---

## 💡 Features possibles post-MVP

1. **Recherche avancée**: Par prix, genre, disponibilité
2. **Favoris**: ❤️ sur les vinyles
3. **Fil d'actu**: Derniers arrivages
4. **Avis clients**: Sur les vinyles/commandes

---

**Prochaine action**: Tests fonctionnels complets ce soir à 20h+
