# ✅ Pré-déploiement - Vérifier avant MEP

## 🔧 Config Production
- [ ] .env: APP_ENV=production
- [ ] .env: APP_DEBUG=false
- [ ] .env: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD (Hostinger)
- [ ] .env: STRIPE_KEY (clés LIVE - pas test)
- [ ] php artisan config:cache
- [ ] php artisan route:cache
- [ ] php artisan view:cache

## 📁 Fichiers à vérifier sur Hostinger
- [ ] public/.htaccess configuré
- [ ] public/index.php pointe vers ../vendor/autoload.php
- [ ] storage/ et bootstrap/cache/ en 755
- [ ] .env présent et correct

## 🗄️ Base de données Hostinger
- [ ] Base vinyles_stock créée
- [ ] migrate:fresh --force exécuté
- [ ] Seeders exécutés (40 vinyles, 3 utilisateurs, 3 fonds)
- [ ] Droits MySQL OK

## 🚀 Post-déploiement
- [ ] Site accessible sur fundisc.fr
- [ ] SSL activé (https://)
- [ ] Test paiement Stripe (mode TEST d'abord!)
- [ ] Commande test passée jusqu'au bout

