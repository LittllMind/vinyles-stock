import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Toast notification system
document.addEventListener('alpine:init', () => {
    Alpine.data('toastData', () => ({
        toasts: [],
        
        init() {
            // Check for session toast on page load from hidden div
            const sessionDataEl = document.getElementById('session-toast-data');
            if (sessionDataEl) {
                try {
                    const toastData = JSON.parse(sessionDataEl.dataset.toast);
                    if (toastData) {
                        this.addToast(toastData);
                    }
                } catch (e) {
                    console.error('Error parsing toast data:', e);
                }
            }
        },
        
        addToast(toast) {
            const id = Date.now() + Math.random();
            const icons = {
                success: '✅',
                error: '❌',
                info: 'ℹ️',
                warning: '⚠️'
            };
            
            this.toasts.push({
                id: id,
                type: toast.type || 'info',
                message: toast.message,
                icon: icons[toast.type] || icons['info'],
                show: true
            });
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                this.removeToast(id);
            }, 3000);
        },
        
        removeToast(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) {
                toast.show = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 100);
            }
        }
    }));
});

Alpine.start();
