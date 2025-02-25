
<?php
/**
 * Reports Page
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function sms_add_reports_page() {
    add_menu_page(
        'Service Reports',
        'Service Reports',
        'manage_options',
        'service-reports',
        'sms_render_reports_page',
        'dashicons-chart-bar',
        6
    );
}
add_action('admin_menu', 'sms_add_reports_page');

// Get filtered service requests for reports
function sms_get_filtered_service_requests($date_from, $date_to, $status, $technician) {
    $args = [
        'post_type' => 'service_request',
        'posts_per_page' => -1,
        'meta_query' => [],
        'date_query' => []
    ];

    if($date_from && $date_to) {
        $args['date_query'][] = [
            'after' => $date_from,
            'before' => $date_to,
            'inclusive' => true
        ];
    }

    if($status) {
        $args['meta_query'][] = [
            'key' => 'status',
            'value' => $status
        ];
    }

    if($technician) {
        $args['meta_query'][] = [
            'key' => 'assigned_technician',
            'value' => $technician
        ];
    }

    return get_posts($args); 
}

// Get summary statistics for reports
function sms_get_reports_summary($date_from, $date_to, $status, $technician) {
    $requests = sms_get_filtered_service_requests($date_from, $date_to, $status, $technician);
    
    $summary = [
        'total' => count($requests),
        'completed' => 0,
        'in_progress' => 0,
        'new' => 0
    ];

    foreach($requests as $request) {
        $request_status = get_field('status', $request->ID);
        if(isset($summary[$request_status])) {
            $summary[$request_status]++;
        }
    }

    return $summary;
}

// Render Reports Page
function sms_render_reports_page() {
    // Get filter values
    $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
    $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
    $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
    $technician = isset($_GET['technician']) ? intval($_GET['technician']) : '';
    ?>
    <div class="wrap">
        <h1>Service Reports</h1>

        <!-- Filters -->
        <div class="tablenav top">
            <form method="get" action="">
                <input type="hidden" name="page" value="service-reports">
                
                <div class="alignleft actions">
                    <input type="date" name="date_from" value="<?php echo esc_attr($date_from); ?>" placeholder="From Date">
                    <input type="date" name="date_to" value="<?php echo esc_attr($date_to); ?>" placeholder="To Date">
                    
                    <select name="status">
                        <option value="">All Statuses</option>
                        <option value="new" <?php selected($status, 'new'); ?>>New</option>
                        <option value="assigned" <?php selected($status, 'assigned'); ?>>Assigned</option>
                        <option value="in_progress" <?php selected($status, 'in_progress'); ?>>In Progress</option>
                        <option value="completed" <?php selected($status, 'completed'); ?>>Completed</option>
                    </select>

                    <select name="technician">
                        <option value="">All Technicians</option>
                        <?php
                        $technicians = get_posts([
                            'post_type' => 'technician',
                            'posts_per_page' => -1
                        ]);
                        foreach($technicians as $tech) {
                            echo sprintf(
                                '<option value="%d" %s>%s</option>',
                                $tech->ID,
                                selected($technician, $tech->ID, false),
                                esc_html($tech->post_title)
                            );
                        }
                        ?>
                    </select>

                    <input type="submit" class="button" value="Filter">
                    <a href="?page=service-reports" class="button">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <?php
        $summary = sms_get_reports_summary($date_from, $date_to, $status, $technician);
        ?>
        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Requests</h3>
                <p class="count"><?php echo $summary['total']; ?></p>
            </div>
            <div class="summary-card">
                <h3>Completed</h3>
                <p class="count"><?php echo $summary['completed']; ?></p>
            </div>
            <div class="summary-card">
                <h3>In Progress</h3>
                <p class="count"><?php echo $summary['in_progress']; ?></p>
            </div>
            <div class="summary-card">
                <h3>New/Unassigned</h3>
                <p class="count"><?php echo $summary['new']; ?></p>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <?php
        $service_requests = sms_get_filtered_service_requests($date_from, $date_to, $status, $technician);
        if($service_requests): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Service Type</th>
                        <th>Technician</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($service_requests as $request): 
                        $user = get_user_by('id', $request->post_author);
                        $tech = get_field('assigned_technician', $request->ID);
                        $status = get_field('status', $request->ID);
                        ?>
                        <tr>
                            <td>#<?php echo $request->ID; ?></td>
                            <td><?php echo get_the_date('Y-m-d', $request->ID); ?></td>
                            <td><?php echo $user ? $user->display_name : 'N/A'; ?></td>
                            <td><?php echo get_field('equipment_type', $request->ID); ?></td>
                            <td><?php echo is_object($tech) ? $tech->post_title : 'Not Assigned'; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo esc_attr($status); ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo get_edit_post_link($request->ID); ?>" class="button button-small">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No service requests found matching your criteria.</p>
        <?php endif; ?>

        <!-- Export Form -->
        <div class="export-section">
            <h3>Export Reports</h3>
            <form method="post" action="">
                <input type="hidden" name="date_from" value="<?php echo esc_attr($date_from); ?>">
                <input type="hidden" name="date_to" value="<?php echo esc_attr($date_to); ?>">
                <input type="hidden" name="status" value="<?php echo esc_attr($status); ?>">
                <input type="hidden" name="technician" value="<?php echo esc_attr($technician); ?>">
                <button type="submit" name="export_reports" class="button button-primary">Export to CSV</button>
            </form>
        </div>
    </div>
    <?php
}