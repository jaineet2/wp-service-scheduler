<?php
/**
 * Customer Dashboard Shortcode
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function sms_customer_dashboard_shortcode() {
    if(!is_user_logged_in()) {
        return 'Please log in to view your dashboard.';
    }

    $user_id = get_current_user_id();
    $requests = get_posts([
        'post_type' => 'service_request',
        'author' => $user_id,
        'posts_per_page' => -1
    ]);

    ob_start();
    ?>
    <div class="customer-dashboard">
        <h2>Your Service Requests</h2>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Requests</h3>
                <p><?php echo count($requests); ?></p>
            </div>
            <div class="stat-card">
                <h3>Active Requests</h3>
                <p><?php echo count(array_filter($requests, function($req) {
                    $status = get_field('status', $req->ID);
                    return in_array($status, ['new', 'assigned', 'in_progress']);
                })); ?></p>
            </div>
        </div>
        
        <h3>Recent Requests</h3>
        <div class="request-list">
            <?php foreach($requests as $request): ?>
                <div class="request-card">
                    <h4><?php echo $request->post_title; ?></h4>
                    <p>Status: <?php echo ucfirst(get_field('status', $request->ID)); ?></p>
                    <p>Equipment: <?php echo ucfirst(get_field('equipment_type', $request->ID)); ?></p>
                    <?php 
                    $tech = get_field('assigned_technician', $request->ID);
                    if($tech): ?>
                        <p>Technician: 
                            <?php 
                            echo is_array($tech) && isset($tech['post_title']) 
                                ? $tech['post_title'] 
                                : (is_object($tech) && isset($tech->post_title) 
                                    ? $tech->post_title 
                                    : 'Not assigned'); 
                            ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('customer_dashboard', 'sms_customer_dashboard_shortcode');