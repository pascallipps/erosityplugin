<?php
/**
 * Admin Statistics
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Admin Statistics Class
 */
class Erosity_Admin_Statistics {
    
    /**
     * Initialize
     */
    public static function init() {
        // Statistics initialization
    }
    
    /**
     * Render statistics page
     */
    public static function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        global $wpdb;
        
        // Get commission statistics
        $total_commission = $wpdb->get_var(
            "SELECT SUM(commission_amount) FROM {$wpdb->prefix}erosity_commissions WHERE status = 'paid'"
        );
        
        $pending_commission = $wpdb->get_var(
            "SELECT SUM(commission_amount) FROM {$wpdb->prefix}erosity_commissions WHERE status = 'pending'"
        );
        
        $expected_commission = $wpdb->get_var(
            "SELECT SUM(commission_amount) FROM {$wpdb->prefix}erosity_commissions WHERE status = 'expected'"
        );
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="erosity-statistics">
                <h2><?php _e('Commission Overview', 'erosity'); ?></h2>
                
                <div class="erosity-stats-grid">
                    <div class="erosity-stat-card">
                        <h3><?php _e('Total Earned', 'erosity'); ?></h3>
                        <div class="stat-value"><?php echo number_format($total_commission ?? 0, 2); ?> €</div>
                    </div>
                    
                    <div class="erosity-stat-card">
                        <h3><?php _e('Pending', 'erosity'); ?></h3>
                        <div class="stat-value"><?php echo number_format($pending_commission ?? 0, 2); ?> €</div>
                    </div>
                    
                    <div class="erosity-stat-card">
                        <h3><?php _e('Expected', 'erosity'); ?></h3>
                        <div class="stat-value"><?php echo number_format($expected_commission ?? 0, 2); ?> €</div>
                    </div>
                </div>
                
                <h2><?php _e('Recent Commissions', 'erosity'); ?></h2>
                <?php self::render_commission_table(); ?>
                
                <h2><?php _e('Booking Statistics', 'erosity'); ?></h2>
                <?php self::render_booking_stats(); ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render commission table
     */
    private static function render_commission_table() {
        global $wpdb;
        
        $commissions = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}erosity_commissions ORDER BY created_at DESC LIMIT 20"
        );
        
        if (empty($commissions)) {
            echo '<p>' . __('No commissions yet.', 'erosity') . '</p>';
            return;
        }
        
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Booking ID', 'erosity'); ?></th>
                    <th><?php _e('Vendor', 'erosity'); ?></th>
                    <th><?php _e('Booking Amount', 'erosity'); ?></th>
                    <th><?php _e('Commission', 'erosity'); ?></th>
                    <th><?php _e('Status', 'erosity'); ?></th>
                    <th><?php _e('Date', 'erosity'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commissions as $commission): ?>
                <tr>
                    <td><?php echo esc_html($commission->booking_id); ?></td>
                    <td><?php echo esc_html(get_userdata($commission->vendor_id)->display_name); ?></td>
                    <td><?php echo number_format($commission->booking_amount, 2); ?> €</td>
                    <td><?php echo number_format($commission->commission_amount, 2); ?> €</td>
                    <td><?php echo esc_html($commission->status); ?></td>
                    <td><?php echo esc_html($commission->created_at); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
    
    /**
     * Render booking statistics
     */
    private static function render_booking_stats() {
        $total_bookings = wp_count_posts('erosity_booking');
        
        ?>
        <div class="erosity-stats-grid">
            <div class="erosity-stat-card">
                <h3><?php _e('Total Bookings', 'erosity'); ?></h3>
                <div class="stat-value"><?php echo $total_bookings->publish + $total_bookings->pending; ?></div>
            </div>
            
            <div class="erosity-stat-card">
                <h3><?php _e('Active Bookings', 'erosity'); ?></h3>
                <div class="stat-value"><?php echo $total_bookings->publish; ?></div>
            </div>
            
            <div class="erosity-stat-card">
                <h3><?php _e('Pending Bookings', 'erosity'); ?></h3>
                <div class="stat-value"><?php echo $total_bookings->pending; ?></div>
            </div>
        </div>
        <?php
    }
}
