<?php
$user = getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderName = sanitizeInput($_POST['sender_name'] ?? '');
    $senderPhone = sanitizeInput($_POST['sender_phone'] ?? '');
    $senderAddress = sanitizeInput($_POST['sender_address'] ?? '');
    $receiverName = sanitizeInput($_POST['receiver_name'] ?? '');
    $receiverPhone = sanitizeInput($_POST['receiver_phone'] ?? '');
    $receiverAddress = sanitizeInput($_POST['receiver_address'] ?? '');
    $pickupCity = sanitizeInput($_POST['pickup_city'] ?? '');
    $deliveryCity = sanitizeInput($_POST['delivery_city'] ?? '');
    $courierType = sanitizeInput($_POST['courier_type'] ?? '');
    $weight = floatval($_POST['weight'] ?? 0);
    $dimensions = sanitizeInput($_POST['dimensions'] ?? '');
    $deliveryDate = sanitizeInput($_POST['delivery_date'] ?? '');
    $notes = sanitizeInput($_POST['notes'] ?? '');
    
    // Validation
    if (empty($senderName) || empty($receiverName) || empty($pickupCity) || empty($deliveryCity)) {
        $error = 'Please fill in all required fields';
    } elseif ($weight <= 0) {
        $error = 'Weight must be greater than 0';
    } elseif (strtotime($deliveryDate) <= time()) {
        $error = 'Delivery date must be in the future';
    } else {
        try {
            $db = getDB();
            $trackingNumber = generateTrackingNumber();
            $cost = calculateCourierCost($weight, $courierType);
            
            $sql = "INSERT INTO couriers (tracking_number, sender_name, sender_phone, sender_address, 
                                        receiver_name, receiver_phone, receiver_address, pickup_city, 
                                        delivery_city, courier_type, weight, dimensions, delivery_date, 
                                        notes, cost, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $db->query($sql, [
                $trackingNumber, $senderName, $senderPhone, $senderAddress,
                $receiverName, $receiverPhone, $receiverAddress, $pickupCity,
                $deliveryCity, $courierType, $weight, $dimensions, $deliveryDate,
                $notes, $cost, $user['id']
            ]);
            
            $courierId = $db->lastInsertId();
            
            // Add initial tracking entry
            addTrackingUpdate($courierId, 'pending', $pickupCity, 'Courier created and awaiting pickup', $user['id']);
            
            $success = "Courier created successfully! Tracking Number: {$trackingNumber}";
            
            // Reset form
            $_POST = [];
            
        } catch (Exception $e) {
            $error = 'Error creating courier: ' . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 text-primary">Add New Courier</h1>
                    <p class="text-muted">Create a new courier shipment</p>
                </div>
                <a href="<?php echo pageUrl('admin-couriers'); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Couriers
                </a>
            </div>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box"></i> Courier Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Sender Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-up"></i> Sender Information
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="sender_name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="sender_name" name="sender_name" 
                                           value="<?php echo htmlspecialchars($_POST['sender_name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="sender_phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="sender_phone" name="sender_phone"
                                           value="<?php echo htmlspecialchars($_POST['sender_phone'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="sender_address" class="form-label">Address</label>
                                    <textarea class="form-control" id="sender_address" name="sender_address" rows="3"><?php echo htmlspecialchars($_POST['sender_address'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="pickup_city" class="form-label">Pickup City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pickup_city" name="pickup_city"
                                           value="<?php echo htmlspecialchars($_POST['pickup_city'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Receiver Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-down"></i> Receiver Information
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="receiver_name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="receiver_name" name="receiver_name"
                                           value="<?php echo htmlspecialchars($_POST['receiver_name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="receiver_phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="receiver_phone" name="receiver_phone"
                                           value="<?php echo htmlspecialchars($_POST['receiver_phone'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="receiver_address" class="form-label">Address</label>
                                    <textarea class="form-control" id="receiver_address" name="receiver_address" rows="3"><?php echo htmlspecialchars($_POST['receiver_address'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="delivery_city" class="form-label">Delivery City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="delivery_city" name="delivery_city"
                                           value="<?php echo htmlspecialchars($_POST['delivery_city'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Package Information -->
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-box-seam"></i> Package Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="courier_type" class="form-label">Courier Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="courier_type" name="courier_type" required>
                                        <option value="">Select Type</option>
                                        <option value="Express" <?php echo ($_POST['courier_type'] ?? '') === 'Express' ? 'selected' : ''; ?>>Express</option>
                                        <option value="Standard" <?php echo ($_POST['courier_type'] ?? '') === 'Standard' ? 'selected' : ''; ?>>Standard</option>
                                        <option value="Economy" <?php echo ($_POST['courier_type'] ?? '') === 'Economy' ? 'selected' : ''; ?>>Economy</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="weight" name="weight" step="0.1" min="0.1"
                                           value="<?php echo htmlspecialchars($_POST['weight'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dimensions" class="form-label">Dimensions (L x W x H)</label>
                                    <input type="text" class="form-control" id="dimensions" name="dimensions" 
                                           placeholder="e.g., 30x20x15 cm"
                                           value="<?php echo htmlspecialchars($_POST['dimensions'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Expected Delivery Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="delivery_date" name="delivery_date"
                                           value="<?php echo htmlspecialchars($_POST['delivery_date'] ?? ''); ?>" 
                                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes or special instructions"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> Create Courier
                        </button>
                        <a href="<?php echo pageUrl('admin-couriers'); ?>" class="btn btn-secondary btn-lg ms-2">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Cost Calculator -->
        <div class="col-lg-4">
            <div class="card position-sticky" style="top: 20px;">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calculator"></i> Cost Calculator
                    </h5>
                </div>
                <div class="card-body">
                    <div id="costCalculation">
                        <p class="text-muted">Fill in the weight and type to calculate cost</p>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Pricing Information:</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Base Cost:</strong> $10.00</li>
                            <li><strong>Weight:</strong> $2.00 per kg</li>
                            <li><strong>Express:</strong> +$15.00</li>
                            <li><strong>Standard:</strong> +$5.00</li>
                            <li><strong>Economy:</strong> +$0.00</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cost calculator
function calculateCost() {
    const weight = parseFloat(document.getElementById('weight').value) || 0;
    const type = document.getElementById('courier_type').value;
    const costDiv = document.getElementById('costCalculation');
    
    if (weight > 0 && type) {
        const baseCost = 10;
        const weightCost = weight * 2;
        let typeCost = 0;
        
        switch (type) {
            case 'Express':
                typeCost = 15;
                break;
            case 'Standard':
                typeCost = 5;
                break;
            case 'Economy':
                typeCost = 0;
                break;
        }
        
        const totalCost = baseCost + weightCost + typeCost;
        
        costDiv.innerHTML = `
            <div class="text-center">
                <h4 class="text-primary">$${totalCost.toFixed(2)}</h4>
                <small class="text-muted">
                    Base: $${baseCost} + Weight: $${weightCost.toFixed(2)} + Type: $${typeCost}
                </small>
            </div>
        `;
    } else {
        costDiv.innerHTML = '<p class="text-muted">Fill in the weight and type to calculate cost</p>';
    }
}

// Attach event listeners
document.getElementById('weight').addEventListener('input', calculateCost);
document.getElementById('courier_type').addEventListener('change', calculateCost);
</script>