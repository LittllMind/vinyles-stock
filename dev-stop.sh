#!/bin/bash
# ═══════════════════════════════════════════════════════════════════════════
# STOP DEV ENVIRONMENT — Kill Laravel and Vite servers
# ═══════════════════════════════════════════════════════════════════════════

echo "🛑 ==============================================="
echo "🛑  Stopping Development Environment"
echo "🛑 ==============================================="
echo ""

# Kill Laravel dev server
echo "🔻 Stopping Laravel dev server..."
killall -q php 2>/dev/null && echo "   ✓ Laravel stopped"

# Kill Vite dev server
echo "🔻 Stopping Vite dev server..."
pkill -f "vite" 2> /dev/null && echo "   ✓ Vite stopped"

echo ""
echo "✅ Development environment stopped"
echo ""

# Verify
LARAVEL_RUNNING=$(lsof -i :8000 2>/dev/null | grep -c php || echo 0)
VITE_RUNNING=$(lsof -i :5173 2>/dev/null | grep -c node || echo 0)

echo "=== VERIFICATION ==="
if [ "$LARAVEL_RUNNING" -eq 0 ]; then
    echo "✓ Port 8000 (Laravel):    Free"
else
    echo "✗ Port 8000 (Laravel):    Still in use"
fi

if [ "$VITE_RUNNING" -eq 0 ]; then
    echo "✓ Port 5173 (Vite):       Free"
else
    echo "✗ Port 5173 (Vite):       Still in use"
fi
