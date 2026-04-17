#!/bin/bash
set -e

###############################################################
# DEPLOIEMENT ATOMIC ZERO-DOWNTIME - VINYLES STOCK
# Hostinger Shared Hosting avecreleases folder + symlink atomique
###############################################################

APP_NAME="vinyles-stock"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
RELEASE_NAME="release_${TIMESTAMP}"

# Config SSH Hostinger
REMOTE_USER="u417457839"
REMOTE_HOST="195.35.49.242"
REMOTE_PORT="65002"
REMOTE_BASE="/home/${REMOTE_USER}/projects/${APP_NAME}"
DOMAIN_PATH="/home/${REMOTE_USER}/domains/la-main-a-la-pate.online"

echo "🚀 Déploiement ${APP_NAME} - ${TIMESTAMP}"
echo "================================================"

# ============================================
# ÉTAPE 1: BUILD LOCAL
# ============================================
echo "[1/6] Build local..."

# npm ci && npm run build déjà fait normalement
if [ ! -d "public/build" ]; then
    echo "⚠️  Build manquant - npm run build..."
    npm ci && npm run build
fi

# Composer sans dev
composer install --no-dev --optimize-autoloader --no-interaction

echo "✅ Build OK"

# ============================================
# ÉTAPE 2: UPLOAD NOUVELLE VERSION
# ============================================
echo "[2/6] Upload sur serveur..."

ssh -p ${REMOTE_PORT} ${REMOTE_USER}@${REMOTE_HOST} "mkdir -p ${REMOTE_BASE}/releases/${RELEASE_NAME}"

rsync -avz --delete \
    --exclude='.env' \
    --exclude='.env.local' \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='tests' \
    --exclude='docs' \
    --exclude='scripts/*.sh' \
    --exclude='*.log' \
    --exclude='storage' \
    --exclude='vendor' \
    -e "ssh -p ${REMOTE_PORT}" \
    ./ ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_BASE}/releases/${RELEASE_NAME}/

# Upload vendor séparément (huge)
echo "[2b] Upload vendor/..."
rsync -avz \
    --include='vendor/**' \
    -e "ssh -p ${REMOTE_PORT}" \
    ./vendor/ ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_BASE}/releases/${RELEASE_NAME}/vendor/

echo "✅ Upload OK"

# ============================================
# ÉTAPE 3: SYMLINKS SHARED
# ============================================
echo "[3/6] Création des liens symboliques..."

ssh -p ${REMOTE_PORT} ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    set -e
    
    # Créer shared si premier déploiement
    if [ ! -d "${REMOTE_BASE}/shared" ]; then
        mkdir -p ${REMOTE_BASE}/shared/storage/app/public
        mkdir -p ${REMOTE_BASE}/shared/storage/framework/{cache,sessions,testing,views}
        mkdir -p ${REMOTE_BASE}/shared/storage/logs
        mkdir -p ${REMOTE_BASE}/shared/uploads
        mkdir -p ${REMOTE_BASE}/backups
        echo "📁 Structure shared créée"
    fi
    
    # Symlink .env de production
    if [ ! -f "${REMOTE_BASE}/shared/.env" ]; then
        cp ${REMOTE_BASE}/releases/${RELEASE_NAME}/.env.production.example ${REMOTE_BASE}/shared/.env
        echo "⚠️  ATTENTION: .env créé depuis template - VÉRIFIER LES SECRETS!"
    fi
    
    # Lien symbolique vers shared
    ln -sfn ${REMOTE_BASE}/shared/.env ${REMOTE_BASE}/releases/${RELEASE_NAME}/.env
    rm -rf ${REMOTE_BASE}/releases/${RELEASE_NAME}/storage
    ln -sfn ${REMOTE_BASE}/shared/storage ${REMOTE_BASE}/releases/${RELEASE_NAME}/storage
    
    # Créer lien storage vers uploads public
    rm -rf ${REMOTE_BASE}/releases/${RELEASE_NAME}/public/storage
    mkdir -p ${REMOTE_BASE}/shared/uploads/vinyles
    ln -sfn ${REMOTE_BASE}/shared/storage/app/public ${REMOTE_BASE}/releases/${RELEASE_NAME}/public/storage
    
    # Permissions
    chmod 755 ${REMOTE_BASE}/releases/${RELEASE_NAME}
    find ${REMOTE_BASE}/releases/${RELEASE_NAME} -type f -exec chmod 644 {} \;
    find ${REMOTE_BASE}/releases/${RELEASE_NAME} -type d -exec chmod 755 {} \;
    chmod -R 775 ${REMOTE_BASE}/shared/storage
    
    echo "✅ Symlinks OK"
ENDSSH

# ============================================
# ÉTAPE 4: POST-DEPLOY (avant switch)
# ============================================
echo "[4/6] Optimisations Laravel (avant switch)..."

ssh -p ${REMOTE_PORT} ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    set -e
    cd ${REMOTE_BASE}/releases/${RELEASE_NAME}
    
    # Migrations
    php artisan migrate --force || {
        echo "❌ ERREUR MIGRATION - rollback manuel nécessaire"
        exit 1
    }
    
    # Caches
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    
    echo "✅ Optimisations OK"
ENDSSH

# ============================================
# ÉTAPE 5: SWITCH ATOMIQUE
# ============================================
echo "[5/6] Switch atomique (zero downtime)..."

ssh -p ${REMOTE_PORT} ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    set -e
    
    # Backup base de données avant switch
    echo "💾 Backup DB..."
    DB_NAME=\$(grep DB_DATABASE ${REMOTE_BASE}/shared/.env | cut -d= -f2)
    DB_USER=\$(grep DB_USERNAME ${REMOTE_BASE}/shared/.env | cut -d= -f2)
    DB_PASS=\$(grep DB_PASSWORD ${REMOTE_BASE}/shared/.env | cut -d= -f2)
    
    mysqldump -u\${DB_USER} -p\${DB_PASS} \${DB_NAME} > ${REMOTE_BASE}/backups/backup_\$(date +%Y%m%d_%H%M%S).sql 2>/dev/null || echo "⚠️  Backup DB manuel recommandé"
    
    # SWITCH ATOMIQUE - Instantané, aucun downtime
    ln -sfn ${REMOTE_BASE}/releases/${RELEASE_NAME} ${REMOTE_BASE}/current
    
    # Mettre à jour le public_html du domaine
    ln -sfn ${REMOTE_BASE}/current/public ${DOMAIN_PATH}/public_html
    
    echo "✅ Switch OK - Nouvelle version active"
ENDSSH

# ============================================
# ÉTAPE 6: CLEANUP
# ============================================
echo "[6/6] Cleanup (garde 5 dernières releases)..."

ssh -p ${REMOTE_PORT} ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    cd ${REMOTE_BASE}/releases
    ls -t | tail -n +6 | xargs -r rm -rf
    echo "🧹 Cleanup OK"
ENDSSH

# ============================================
# RÉSUMÉ
# ============================================
echo ""
echo "================================================"
echo "✅ DÉPLOIEMENT RÉUSSI!"
echo "================================================"
echo "📦 Release: ${RELEASE_NAME}"
echo "🌐 Site: https://la-main-a-la-pate.online"
echo "🔗 Path: ${REMOTE_BASE}/current"
echo "================================================"

# Test rapide
echo "[Bonus] Test HTTP..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://la-main-a-la-pate.online)
if [ "$HTTP_CODE" -eq 200 ]; then
    echo "✅ Site OK (HTTP 200)"
else
    echo "⚠️  HTTP ${HTTP_CODE} - vérifier logs"
fi
