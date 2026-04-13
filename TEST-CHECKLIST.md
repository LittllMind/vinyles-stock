# 📋 Checklist Test - Vinyles ART PRINT
**Date**: Ce soir après 20h  
**URL**: http://10.5.0.2:8000  
**Objectif**: Valider fonctionnement PC + Mobile avant déploiement

---

## ✅ 1. Navigation Globale (PC + Mobile)
- [ ] La page d'accueil charge correctement
- [ ] Navigation responsive (menu s'adapte mobile)
- [ ] Logo "ART PRINT" visible
- [ ] Liens: Collection, À propos, Contact, Connexion
- [ ] **Mobile**: Le hamburger menu fonctionne

**Résultat**: ___

---

## ✅ 2. Catalogue Vinyles
- [ ] `/kiosque` affiche la grille de vinyles
- [ ] **24 vinyles** visibles sur la première page
- [ ] Pagination fonctionne (page 2 accessible)
- [ ] Prix affichés: € 25,00, € 28,00, etc.
- [ ] Genres visibles sous chaque vinyle (Rock, Rap, Pop...)
- [ ] Recherche fonctionnelle (tester "Bowie", "Daft Punk")

**Résultat**: ___

---

## ✅ 3. Sélection Fonds (FEATURE PRIORITAIRE)
C'est la fonction clé à tester minutieusement :

### Test 1: Modale s'ouvre
- [ ] Cliquer sur le **bouton +** d'un vinyle
- [ ] La modale s'affiche avec les 3 options

### Test 2: Options de fond
- [ ] **Sans fond**: 0€ ajouté
- [ ] **Miroir Argenté**: +8€ au prix
- [ ] **Doré**: +13€ au prix

### Test 3: Calcul prix
- [ ] Prix total se met à jour en temps réél
- [ ] Ex: David Bowie €25,00 + Miroir €8,00 = **€33,00**

### Test 4: Ajout panier
- [ ] Cliquer "Ajouter au panier"
- [ ] Le vinyle apparaît dans le panier
- [ ] Quantité correcte (1)
- [ ] Prix total OK

**Résultat**: ___

---

## ✅ 4. Détail Vinyle
- [ ] Cliquer sur un vinyle → page détail
- [ ] Photo, artiste, album, genre visibles
- [ ] Stock disponible affiché
- [ ] Bouton ajout panier fonctionne

**Résultat**: ___

---

## ✅ 5. Panier & Commande
- [ ] Panier accessible depuis l'icône 🛒
- [ ] Modifier quantité fonctionne
- [ ] Supprimer un article fonctionne
- [ ] Total calculé correctement (vinyle + fond)

**Résultat**: ___

---

## ✅ 6. Authentification
- [ ] Page connexion accessible
- [ ] **Admin**: admin@test.com / password123
- [ ] **Client**: client@test.com / password123
- [ ] Dashboard admin s'affiche (sidebar visible)

**Résultat**: ___

---

## ✅ 7. Responsive Mobile
- [ ] Tester sur smartphone (iOS ou Android)
- [ ] Grille s'adapte (1 colonne en portrait)
- [ ] Modale fonds utilisable à une main
- [ ] Pas de débordement horizontal
- [ ] Boutons + facilement cliquables

**Résultat**: ___

---

## ✅ 8. Performance
- [ ] Chargement < 3 secondes
- [ ] Images des vinyles apparaissent
- [ ] Pas d'erreur JavaScript (console navigateur)

**Résultat**: ___

---

## 🐛 Bugs Trouvés
| # | Problème | Screenshot | Sévérité |
|---|----------|------------|----------|
| 1 | | | |
| 2 | | | |
| 3 | | | |

---

## 📸 Screenshots à prendre
1. Page d'accueil (desktop)
2. Catalogue (mobile)
3. Modale fonds ouverte
4. Panier avec article

---

## 🚀 Si tout est OK : Prochaines étapes
1. Commit sur GitHub
2. Déploiement Hostinger
3. Configuration domaine fundisc.fr
4. Tests en production

---

**Test réalisé par**: ___  
**Heure de début**: ___  
**Heure de fin**: ___  
**Verdict global**: ☐ OK / ☐ Des bugs à corriger