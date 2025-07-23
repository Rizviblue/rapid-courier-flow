/**
 * Main JavaScript file for Courier Management System
 */

// Global variables
let currentUser = null;
let notifications = [];

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize modals
    initializeModals();
    
    // Initialize forms
    initializeForms();
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize notifications
    initializeNotifications();
    
    // Initialize real-time updates (if needed)
    // initializeRealTimeUpdates();
}

// Tooltip functionality
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(event) {
    const element = event.target;
    const tooltipText = element.getAttribute('data-tooltip');
    
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = tooltipText;
    tooltip.style.position = 'absolute';
    tooltip.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    tooltip.style.color = 'white';
    tooltip.style.padding = '0.5rem';
    tooltip.style.borderRadius = '0.25rem';
    tooltip.style.fontSize = '0.75rem';
    tooltip.style.zIndex = '1000';
    tooltip.style.pointerEvents = 'none';
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
    
    element._tooltip = tooltip;
}

function hideTooltip(event) {
    const element = event.target;
    if (element._tooltip) {
        document.body.removeChild(element._tooltip);
        element._tooltip = null;
    }
}

// Modal functionality
function initializeModals() {
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeModal(event.target.querySelector('.modal-content'));
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModal = document.querySelector('.modal-overlay');
            if (openModal) {
                closeModal(openModal.querySelector('.modal-content'));
            }
        }
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modal) {
    if (modal) {
        const overlay = modal.closest('.modal-overlay');
        if (overlay) {
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
}

// Form functionality
function initializeForms() {
    // Add loading states to forms
    const forms = document.querySelectorAll('form[data-loading]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner"></span> Loading...';
            }
        });
    });
    
    // Real-time validation
    const inputs = document.querySelectorAll('input[data-validate], select[data-validate], textarea[data-validate]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
}

function validateField(event) {
    const field = event.target;
    const rules = field.getAttribute('data-validate').split('|');
    const value = field.value.trim();
    
    let isValid = true;
    let errorMessage = '';
    
    rules.forEach(rule => {
        if (!isValid) return;
        
        const [ruleName, ruleValue] = rule.split(':');
        
        switch (ruleName) {
            case 'required':
                if (!value) {
                    isValid = false;
                    errorMessage = 'This field is required';
                }
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (value && !emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                }
                break;
            case 'min':
                if (value && value.length < parseInt(ruleValue)) {
                    isValid = false;
                    errorMessage = `Minimum ${ruleValue} characters required`;
                }
                break;
            case 'max':
                if (value && value.length > parseInt(ruleValue)) {
                    isValid = false;
                    errorMessage = `Maximum ${ruleValue} characters allowed`;
                }
                break;
        }
    });
    
    showFieldError(field, isValid ? null : errorMessage);
    return isValid;
}

function showFieldError(field, message) {
    // Remove existing error
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    if (message) {
        field.classList.add('border-red-500');
        
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error text-red-600 text-sm mt-1';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    } else {
        field.classList.remove('border-red-500');
    }
}

function clearFieldError(event) {
    const field = event.target;
    field.classList.remove('border-red-500');
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Search functionality
function initializeSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        let searchTimeout;
        
        input.addEventListener('input', function(event) {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                performSearch(event.target);
            }, 300);
        });
    });
}

function performSearch(input) {
    const searchTerm = input.value.toLowerCase();
    const targetSelector = input.getAttribute('data-search');
    const targetElements = document.querySelectorAll(targetSelector);
    
    targetElements.forEach(element => {
        const searchableText = element.textContent.toLowerCase();
        
        if (searchableText.includes(searchTerm)) {
            element.style.display = '';
        } else {
            element.style.display = 'none';
        }
    });
}

// Notification functionality
function initializeNotifications() {
    // Check for flash messages
    const flashMessages = document.querySelectorAll('.flash-message');
    
    flashMessages.forEach(message => {
        setTimeout(() => {
            fadeOut(message);
        }, 5000);
    });
}

function showNotification(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `notification alert alert-${type} fixed top-4 right-4 z-50 max-w-sm`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-lg">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after duration
    setTimeout(() => {
        if (notification.parentNode) {
            fadeOut(notification);
        }
    }, duration);
}

function fadeOut(element) {
    element.style.opacity = '0';
    element.style.transition = 'opacity 0.3s ease-out';
    
    setTimeout(() => {
        if (element.parentNode) {
            element.parentNode.removeChild(element);
        }
    }, 300);
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// AJAX helper functions
function makeRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Request failed:', error);
            showNotification('An error occurred. Please try again.', 'error');
            throw error;
        });
}

// Export functions for global use
window.CourierApp = {
    openModal,
    closeModal,
    showNotification,
    makeRequest,
    formatDate,
    formatCurrency,
    validateField
};

// Print functionality
function printElement(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    const printDocument = printWindow.document;
    
    printDocument.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print</title>
            <link rel="stylesheet" href="assets/css/style.css">
            <style>
                body { margin: 0; padding: 20px; }
                .no-print { display: none !important; }
            </style>
        </head>
        <body>
            ${element.innerHTML}
        </body>
        </html>
    `);
    
    printDocument.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}

// Table sorting functionality
function sortTable(table, column, direction = 'asc') {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aValue = a.cells[column].textContent.trim();
        const bValue = b.cells[column].textContent.trim();
        
        // Try to parse as numbers first
        const aNum = parseFloat(aValue);
        const bNum = parseFloat(bValue);
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        // Sort as strings
        return direction === 'asc' 
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

// Initialize table sorting
document.addEventListener('DOMContentLoaded', function() {
    const sortableHeaders = document.querySelectorAll('[data-sort]');
    
    sortableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const table = this.closest('table');
            const column = parseInt(this.getAttribute('data-sort'));
            const currentDirection = this.getAttribute('data-direction') || 'asc';
            const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            
            // Reset all headers
            sortableHeaders.forEach(h => h.removeAttribute('data-direction'));
            
            // Set new direction
            this.setAttribute('data-direction', newDirection);
            
            sortTable(table, column, newDirection);
        });
    });
});