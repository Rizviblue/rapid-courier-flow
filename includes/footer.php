</main>

<!-- Footer -->
<?php if (isLoggedIn() && !in_array($page, ['login', 'register'])): ?>
<footer class="bg-light text-center text-muted py-3 mt-5">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved. | Version <?php echo APP_VERSION; ?></p>
    </div>
</footer>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js for analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Custom JS -->
<script src="assets/js/app.js"></script>

<script>
// Auto-hide alerts after 5 seconds
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Confirm delete actions
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Real-time search functionality
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    input.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length; j++) {
                if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    });
}

// Track courier functionality
function trackCourier() {
    const trackingNumber = document.getElementById('trackingNumber');
    const trackingResult = document.getElementById('trackingResult');
    
    if (!trackingNumber || !trackingNumber.value.trim()) {
        alert('Please enter a tracking number');
        return;
    }
    
    fetch('index.php?page=api&action=track', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'tracking_number=' + encodeURIComponent(trackingNumber.value)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTrackingResult(data.courier);
        } else {
            trackingResult.innerHTML = '<div class="alert alert-warning">Courier not found</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        trackingResult.innerHTML = '<div class="alert alert-danger">Error occurred while tracking</div>';
    });
}

function displayTrackingResult(courier) {
    const trackingResult = document.getElementById('trackingResult');
    
    let trackingHtml = `
        <div class="card">
            <div class="card-header">
                <h5>Tracking Details - ${courier.tracking_number}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Sender:</strong> ${courier.sender_name}</p>
                        <p><strong>Receiver:</strong> ${courier.receiver_name}</p>
                        <p><strong>From:</strong> ${courier.pickup_city}</p>
                        <p><strong>To:</strong> ${courier.delivery_city}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Type:</strong> ${courier.courier_type}</p>
                        <p><strong>Weight:</strong> ${courier.weight} kg</p>
                        <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(courier.status)}">${courier.status.replace('_', ' ').toUpperCase()}</span></p>
                        <p><strong>Expected Delivery:</strong> ${new Date(courier.delivery_date).toLocaleDateString()}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if (courier.tracking && courier.tracking.length > 0) {
        trackingHtml += `
            <div class="card mt-3">
                <div class="card-header">
                    <h6>Tracking History</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
        `;
        
        courier.tracking.forEach(track => {
            trackingHtml += `
                <div class="timeline-item">
                    <div class="timeline-marker bg-${getStatusColor(track.status)}"></div>
                    <div class="timeline-content">
                        <h6>${track.status.replace('_', ' ').toUpperCase()}</h6>
                        <p class="text-muted">${track.location || ''}</p>
                        <p class="text-muted">${track.notes || ''}</p>
                        <small class="text-muted">${new Date(track.created_at).toLocaleString()}</small>
                    </div>
                </div>
            `;
        });
        
        trackingHtml += `
                    </div>
                </div>
            </div>
        `;
    }
    
    trackingResult.innerHTML = trackingHtml;
}

function getStatusColor(status) {
    switch (status) {
        case 'pending': return 'warning';
        case 'picked_up':
        case 'in_transit': return 'info';
        case 'delivered': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
</script>

</body>
</html>