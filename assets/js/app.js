// Courier Management System - Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Global utility functions
const Utils = {
    // Show loading spinner
    showLoading: function(element) {
        if (element) {
            element.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        }
    },

    // Hide loading spinner
    hideLoading: function(element, originalContent = '') {
        if (element) {
            element.innerHTML = originalContent;
        }
    },

    // Show toast notification
    showToast: function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    },

    // Format date
    formatDate: function(dateString, options = {}) {
        const defaultOptions = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        };
        const formatOptions = { ...defaultOptions, ...options };
        return new Date(dateString).toLocaleDateString('en-US', formatOptions);
    },

    // Format currency
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },

    // Debounce function
    debounce: function(func, wait) {
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
};

// API helper functions
const API = {
    // Make API request
    request: async function(action, data = {}, method = 'POST') {
        try {
            const url = `index.php?page=api&action=${action}`;
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            };

            if (method === 'POST') {
                options.body = new URLSearchParams(data);
            } else if (method === 'GET' && Object.keys(data).length > 0) {
                const params = new URLSearchParams(data);
                url += '&' + params.toString();
            }

            const response = await fetch(url, options);
            const result = await response.json();
            
            return result;
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, message: 'Network error occurred' };
        }
    },

    // Track courier
    trackCourier: async function(trackingNumber) {
        return await this.request('track', { tracking_number: trackingNumber });
    },

    // Update courier status
    updateStatus: async function(courierId, status, location = '', notes = '') {
        return await this.request('update_status', {
            courier_id: courierId,
            status: status,
            location: location,
            notes: notes
        });
    },

    // Delete courier
    deleteCourier: async function(courierId) {
        return await this.request('delete_courier', { courier_id: courierId });
    },

    // Search couriers
    searchCouriers: async function(query, status = '') {
        return await this.request('search_couriers', { q: query, status: status }, 'GET');
    },

    // Get statistics
    getStats: async function() {
        return await this.request('get_stats', {}, 'GET');
    }
};

// Table utilities
const TableUtils = {
    // Initialize search functionality
    initSearch: function(searchInputId, tableId) {
        const searchInput = document.getElementById(searchInputId);
        const table = document.getElementById(tableId);
        
        if (!searchInput || !table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const debouncedSearch = Utils.debounce(function(searchTerm) {
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matches = text.includes(searchTerm.toLowerCase());
                row.style.display = matches ? '' : 'none';
            });
        }, 300);

        searchInput.addEventListener('input', function() {
            debouncedSearch(this.value);
        });
    },

    // Initialize sorting functionality
    initSort: function(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const sortKey = this.dataset.sort;
                const sortOrder = this.dataset.order === 'asc' ? 'desc' : 'asc';
                
                // Update all headers
                headers.forEach(h => h.dataset.order = '');
                this.dataset.order = sortOrder;
                
                // Sort table
                TableUtils.sortTable(table, sortKey, sortOrder);
            });
        });
    },

    // Sort table rows
    sortTable: function(table, key, order) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aVal = a.querySelector(`[data-${key}]`)?.dataset[key] || 
                        a.querySelector(`td:nth-child(${key})`)?.textContent || '';
            const bVal = b.querySelector(`[data-${key}]`)?.dataset[key] || 
                        b.querySelector(`td:nth-child(${key})`)?.textContent || '';
            
            if (order === 'asc') {
                return aVal.localeCompare(bVal, undefined, { numeric: true });
            } else {
                return bVal.localeCompare(aVal, undefined, { numeric: true });
            }
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }
};

// Form utilities
const FormUtils = {
    // Validate form
    validate: function(formId, rules) {
        const form = document.getElementById(formId);
        if (!form) return false;

        let isValid = true;
        
        Object.keys(rules).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            const rule = rules[fieldName];
            
            if (field) {
                const value = field.value.trim();
                let fieldValid = true;
                let errorMessage = '';

                if (rule.required && !value) {
                    fieldValid = false;
                    errorMessage = `${rule.label || fieldName} is required`;
                }

                if (fieldValid && rule.minLength && value.length < rule.minLength) {
                    fieldValid = false;
                    errorMessage = `${rule.label || fieldName} must be at least ${rule.minLength} characters`;
                }

                if (fieldValid && rule.pattern && !rule.pattern.test(value)) {
                    fieldValid = false;
                    errorMessage = rule.message || `${rule.label || fieldName} format is invalid`;
                }

                // Show/hide error
                this.showFieldError(field, fieldValid ? '' : errorMessage);
                
                if (!fieldValid) {
                    isValid = false;
                }
            }
        });

        return isValid;
    },

    // Show field error
    showFieldError: function(field, message) {
        // Remove existing error
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        if (message) {
            field.classList.add('is-invalid');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error text-danger small mt-1';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    },

    // Clear form validation
    clearValidation: function(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const fields = form.querySelectorAll('.form-control, .form-select');
        fields.forEach(field => {
            field.classList.remove('is-invalid', 'is-valid');
        });

        const errors = form.querySelectorAll('.field-error');
        errors.forEach(error => error.remove());
    }
};

// Chart utilities (using Chart.js)
const ChartUtils = {
    // Default chart options
    defaultOptions: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    },

    // Create pie chart
    createPieChart: function(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'pie',
            data: data,
            options: { ...this.defaultOptions, ...options }
        });
    },

    // Create line chart
    createLineChart: function(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'line',
            data: data,
            options: { ...this.defaultOptions, ...options }
        });
    },

    // Create bar chart
    createBarChart: function(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'bar',
            data: data,
            options: { ...this.defaultOptions, ...options }
        });
    }
};

// Export for global use
window.Utils = Utils;
window.API = API;
window.TableUtils = TableUtils;
window.FormUtils = FormUtils;
window.ChartUtils = ChartUtils;