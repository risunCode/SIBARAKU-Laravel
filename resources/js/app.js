import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

// Theme System (Light / Dark)
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);
}

function applyTheme(themeName) {
    const html = document.documentElement;
    html.classList.remove('dark');
    
    if (themeName === 'dark') {
        html.classList.add('dark');
    }
    
    localStorage.setItem('theme', themeName);
    updateThemeChecks(themeName);
}

function updateThemeChecks(currentTheme) {
    const lightCheck = document.querySelector('.theme-check-light');
    const darkCheck = document.querySelector('.theme-check-dark');
    
    if (lightCheck) lightCheck.style.display = currentTheme === 'light' ? 'block' : 'none';
    if (darkCheck) darkCheck.style.display = currentTheme === 'dark' ? 'block' : 'none';
}

window.setTheme = applyTheme;
window.getTheme = () => localStorage.getItem('theme') || 'light';

// Initialize theme on load
initTheme();

// Update checks when DOM ready
document.addEventListener('DOMContentLoaded', () => {
    updateThemeChecks(getTheme());
});

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize SweetAlert2
window.Swal = Swal;

// Toast notification preset
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Helper: Show success toast
window.showSuccess = (message) => {
    Toast.fire({
        icon: 'success',
        title: message
    });
};

// Helper: Show error toast
window.showError = (message) => {
    Toast.fire({
        icon: 'error',
        title: message
    });
};

// Helper: Show warning toast
window.showWarning = (message) => {
    Toast.fire({
        icon: 'warning',
        title: message
    });
};

// Helper: Show info toast
window.showInfo = (message) => {
    Toast.fire({
        icon: 'info',
        title: message
    });
};

// Helper: Confirm delete dialog
window.confirmDelete = (formId, title = 'Hapus Data?', text = 'Data yang dihapus tidak dapat dikembalikan!') => {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

// Helper: Confirm action dialog
window.confirmAction = (callback, title = 'Konfirmasi', text = 'Apakah Anda yakin?', confirmText = 'Ya', icon = 'question') => {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Helper: Loading overlay
window.showLoading = (title = 'Memproses...') => {
    Swal.fire({
        title: title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

window.hideLoading = () => {
    Swal.close();
};

// Format currency to Rupiah
window.formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
};

// Format number with thousand separator
window.formatNumber = (number) => {
    return new Intl.NumberFormat('id-ID').format(number);
};

// Format Rupiah input (for form inputs)
window.formatRupiahInput = (input, hiddenId) => {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    input.value = value;
    if (hiddenId) {
        const hiddenField = document.getElementById(hiddenId);
        if (hiddenField) {
            hiddenField.value = value.replace(/\./g, '') || 0;
        }
    }
};

// Initialize Rupiah display from hidden value
window.initRupiahDisplay = (displayId, hiddenId) => {
    const hiddenVal = document.getElementById(hiddenId);
    const displayVal = document.getElementById(displayId);
    if (hiddenVal && displayVal && hiddenVal.value && parseInt(hiddenVal.value) > 0) {
        displayVal.value = parseInt(hiddenVal.value).toLocaleString('id-ID');
    }
};

// Global Image Viewer using SweetAlert2
window.viewImage = (src, title = '') => {
    Swal.fire({
        imageUrl: src,
        imageAlt: title,
        title: title,
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto',
        padding: '1rem',
        background: '#000',
        customClass: {
            title: 'text-white text-sm',
            closeButton: 'text-white',
            popup: 'rounded-xl'
        }
    });
};
