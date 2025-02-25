<?php
/**
 * Plugin Name: Service Management System
 * Description: Organizes service requests and technician management for appliance repairs
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: service-management-system
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SMS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SMS_PLUGIN_DIR . 'includes/post-types.php';
require_once SMS_PLUGIN_DIR . 'includes/acf-fields.php';
require_once SMS_PLUGIN_DIR . 'includes/admin/reports.php';
require_once SMS_PLUGIN_DIR . 'includes/admin/export.php';
require_once SMS_PLUGIN_DIR . 'public/forms/service-request-form.php';
require_once SMS_PLUGIN_DIR . 'public/shortcodes/service-request-shortcode.php';
require_once SMS_PLUGIN_DIR . 'public/shortcodes/customer-dashboard-shortcode.php';

// Register CSS
function sms_enqueue_styles() {
    wp_enqueue_style('sms-styles', SMS_PLUGIN_URL . 'public/css/sms-styles.css');
}
add_action('wp_enqueue_scripts', 'sms_enqueue_styles');
add_action('admin_enqueue_scripts', 'sms_enqueue_styles');