<?php
/**
 * Service Request Form Shortcode
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function sms_service_request_form_shortcode() {
    if (!is_user_logged_in()) {
        return 'Please log in to submit a service request.';
    }

    $message = isset($_GET['submission']) && $_GET['submission'] === 'success' 
        ? '<div class="success-message">Your service request has been submitted successfully!</div>' 
        : '';

    ob_start();
    ?>
    <?php echo $message; ?>
    <form id="service-request-form" class="service-request-form" method="post" action="">
        <?php wp_nonce_field('submit_service_request', 'service_request_nonce'); ?>
        
        <div class="form-group">
            <label for="equipment_type">Equipment Type *</label>
            <select name="equipment_type" id="equipment_type" required>
                <option value="">Select Equipment</option>
                <option value="ac">AC</option>
                <option value="refrigerator">Refrigerator</option>
                <option value="water_purifier">Water Purifier</option>
                <option value="microwave">Microwave Oven</option>
                <option value="tv">TV</option>
            </select>
        </div>

        <div class="form-group">
            <label for="warranty">Under Warranty?</label>
            <input type="checkbox" name="warranty" id="warranty">
        </div>

        <div class="form-group">
            <label for="purchase_date">Purchase Date *</label>
            <input type="date" name="purchase_date" id="purchase_date" required>
        </div>

        <div class="form-group">
            <label for="brand">Brand *</label>
            <input type="text" name="brand" id="brand" required>
        </div>

        <div class="form-group">
            <label for="description">Problem Description *</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <input type="hidden" name="action" value="submit_service_request">
        <button type="submit" name="submit_service_request">Submit Request</button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('service_request_form', 'sms_service_request_form_shortcode');