#!/bin/bash
# ═══════════════════════════════════════════════════════════════════════════
# START DEV ENVIRONMENT — Laravel + Vite with Auto-Refresh
# ═══════════════════════════════════════════════════════════════════════════

PROJECT_DIR="/home/aur-lien/workspace/projects/vinyles-stock"

echo "🎵 ==============================================="
echo "🎵  Starting Development Environment"
echo "🎵 ==============================================="
echo ""

cd "$PROJECT_DIR"

# Check if already running
LARAVEL_RUNNING=$(lsof -i :8000 2>/dev/null | grep -c php)
VITE_RUNNING=$(lsof -i :5173 2>/dev/null | grep -c node)

if [ "$LARAVEL_RUNNING" -gt 0 ]; then
    echo "✓ Laravel dev server already running on port 8000"
else
    echo "🚀 Starting Laravel dev server..."
    killall -q php 2>/dev/null || true  # Clean up any leftover processes
    php artisan serve --host=127.0.0.1 --port=8000 > /tmp/laravel.log 2>&1 &
    echo "   PID: $! (logs: /tmp/laravel.log)"
fi

if [ "$VITE_RUNNING" -gt 0 ]; then
    echo "✓ Vite dev server already running on port 5173"
else
    echo "⚡ Starting Vite dev server..."
    # Use nohup to ensure Vite keeps running
    nohup npm run dev > /tmp/vite.log 2>&1 &
    echo "   PID: $! (logs: /tmp/vite.log)"
fi

echo ""
echo "═══════════════════════════════════════════════════"
echo "📍 Application: http://127.0.0.1:8000"
echo "📡 Vite HMR:    http://127.0.0.1:5173"
echo "══════════════════════════════════════════════════="
echo ""
echo "📝 Auto-reload: Save any file → automatic browser refresh"
echo ""
echo "To stop: ./dev-stop.sh"
echo ""

# Wait a moment and verify
sleep 2
echo "=== VERIFICATION ==="
if lsof -i :8000 2>/dev/null | grep -q php; then
    echo "✓ Laravel: OK"
else
    echo "✗ Laravel: FAILED"
fi

if lsof -i :5173 2>/dev/null | grep -q node; then
    echo "✓ Vite:    OK"
else
    echo "✗ Vite:    FAILED (check /tmp/vite.log)"
fi
