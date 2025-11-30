import Swal from 'sweetalert2';

class SessionManager {
    constructor() {
        this.checkInterval = 60000; // Check every 60 seconds
        this.warningThreshold = 300; // Show warning 5 minutes (300 seconds) before expiry
        this.countdownInterval = null;
        this.checkTimer = null;
        this.isWarningShown = false;
        
        this.init();
    }

    init() {
        // Only run if user is authenticated and not on public routes
        if (document.querySelector('meta[name="csrf-token"]') && !this.isPublicRoute()) {
            this.startHeartbeat();
        }
    }

    isPublicRoute() {
        const publicRoutes = ['/verify', '/auth', '/'];
        const currentPath = window.location.pathname;
        
        // Check if current path starts with any public route
        return publicRoutes.some(route => currentPath.startsWith(route));
    }

    startHeartbeat() {
        // Initial check
        this.checkSession();
        
        // Start periodic checks
        this.checkTimer = setInterval(() => {
            this.checkSession();
        }, this.checkInterval);
    }

    async checkSession() {
        try {
            const response = await fetch('/api/session/status', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            if (response.status === 401) {
                const data = await response.json();
                if (data.status === 'expired') {
                    this.handleSessionExpired();
                } else if (data.status === 'unauthenticated') {
                    this.handleUnauthenticated();
                }
                return;
            }

            const data = await response.json();
            
            if (data.status === 'active' && data.remaining <= this.warningThreshold && !this.isWarningShown) {
                this.showSessionWarning(data.remaining);
            }
        } catch (error) {
            console.error('Session check failed:', error);
            // If network error, assume session might be valid but don't show warning
        }
    }

    showSessionWarning(remainingSeconds) {
        this.isWarningShown = true;
        
        let timeLeft = Math.floor(remainingSeconds);
        const updateCountdown = () => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        };

        Swal.fire({
            title: '⏰ Sesi Akan Berakhir',
            html: `
                <div class="text-center">
                    <p class="mb-4">Sesi login Anda akan berakhir dalam:</p>
                    <div class="text-3xl font-bold text-orange-500 mb-4" id="session-countdown">
                        ${updateCountdown()}
                    </div>
                    <p class="text-sm text-gray-600">Apakah Anda ingin memperpanjang sesi?</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Perpanjang Sesi',
            cancelButtonText: 'Keluar',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#dc2626',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                // Start countdown
                this.countdownInterval = setInterval(() => {
                    timeLeft--;
                    const countdownEl = document.getElementById('session-countdown');
                    if (countdownEl) {
                        countdownEl.textContent = updateCountdown();
                    }
                    
                    if (timeLeft <= 0) {
                        clearInterval(this.countdownInterval);
                        this.handleSessionExpired();
                    }
                }, 1000);
            },
            willClose: () => {
                // Clear countdown when modal closes
                if (this.countdownInterval) {
                    clearInterval(this.countdownInterval);
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                this.extendSession();
            } else if (result.isDismissed) {
                this.logout();
            }
        });
    }

    async extendSession() {
        try {
            // Make a request to extend the session (not just check)
            const response = await fetch('/api/session/extend', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                this.isWarningShown = false;
                
                Swal.fire({
                    title: 'Sesi Diperpanjang',
                    text: 'Sesi login Anda telah diperpanjang.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                this.handleSessionExpired();
            }
        } catch (error) {
            console.error('Failed to extend session:', error);
            this.handleSessionExpired();
        }
    }

    handleSessionExpired() {
        // Stop heartbeat
        if (this.checkTimer) {
            clearInterval(this.checkTimer);
        }
        
        Swal.fire({
            title: '⏱ Sesi Berakhir',
            text: 'Sesi login Anda telah berakhir. Silakan login kembali.',
            icon: 'info',
            confirmButtonText: 'Login Kembali',
            confirmButtonColor: '#3b82f6',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            // Only redirect if not already on auth page
            if (!window.location.pathname.includes('/auth') && window.location.pathname !== '/') {
                window.location.href = '/';
            }
        });
    }

    handleUnauthenticated() {
        // Stop heartbeat
        if (this.checkTimer) {
            clearInterval(this.checkTimer);
        }
        
        // Don't show any popup for unauthenticated users on public routes
        // Only show if they're trying to access protected areas
        if (!this.isPublicRoute()) {
            Swal.fire({
                title: '🔐 Belum Login',
                text: 'Anda perlu login untuk mengakses halaman ini.',
                icon: 'warning',
                confirmButtonText: 'Login',
                confirmButtonColor: '#3b82f6',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = '/';
            });
        }
    }

    logout() {
        window.location.href = '/logout';
    }

    // Public method to manually check session
    checkNow() {
        this.checkSession();
    }
}

// Initialize session manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.sessionManager = new SessionManager();
});

// Export for potential use in other modules
export default SessionManager;
