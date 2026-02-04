<?php
/**
 * Admin functionality
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Admin Class
 */
class Erosity_Admin {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
        
        // Initialize sub-classes
        Erosity_Admin_Settings::init();
        Erosity_Admin_Statistics::init();
        Erosity_Admin_Support::init();
    }
    
    /**
     * Add admin menu
     */
    public static function add_admin_menu() {
        add_menu_page(
            __('Erosity', 'erosity'),
            __('Erosity', 'erosity'),
            'manage_options',
            'erosity',
            array(__CLASS__, 'render_dashboard'),
            'dashicons-calendar-alt',
            20
        );
        
        add_submenu_page(
            'erosity',
            __('Dashboard', 'erosity'),
            __('Dashboard', 'erosity'),
            'manage_options',
            'erosity',
            array(__CLASS__, 'render_dashboard')
        );
        
        add_submenu_page(
            'erosity',
            __('Settings', 'erosity'),
            __('Settings', 'erosity'),
            'manage_options',
            'erosity-settings',
            array('Erosity_Admin_Settings', 'render_page')
        );
        
        add_submenu_page(
            'erosity',
            __('Statistics', 'erosity'),
            __('Statistics', 'erosity'),
            'manage_options',
            'erosity-statistics',
            array('Erosity_Admin_Statistics', 'render_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public static function enqueue_scripts($hook) {
        if (strpos($hook, 'erosity') === false) {
            return;
        }
        
        wp_enqueue_style(
            'erosity-admin',
            EROSITY_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            EROSITY_VERSION
        );
        
        wp_enqueue_script(
            'erosity-admin',
            EROSITY_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            EROSITY_VERSION,
            true
        );
    }
    
    /**
     * Render dashboard page
     */
    public static function render_dashboard() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="erosity-dashboard">
                <div class="erosity-stats-grid">
                    <div class="erosity-stat-card">
                        <h3><?php _e('Total Properties', 'erosity'); ?></h3>
                        <div class="stat-value"><?php echo wp_count_posts('erosity_property')->publish; ?></div>
                    </div>
                    
                    <div class="erosity-stat-card">
                        <h3><?php _e('Total Toys', 'erosity'); ?></h3>
                        <div class="stat-value"><?php echo wp_count_posts('erosity_toy')->publish; ?></div>
                    </div>
                    
                    <div class="erosity-stat-card">
                        <h3><?php _e('Active Bookings', 'erosity'); ?></h3>
                        <div class="stat-value"><?php echo wp_count_posts('erosity_booking')->publish; ?></div>
                    </div>
                    
                    <div class="erosity-stat-card">
                        <h3><?php _e('Total Reviews', 'erosity'); ?></h3>
                        <div class="stat-value"><?php echo wp_count_posts('erosity_review')->publish; ?></div>
                    </div>
                </div>
                
                <div class="erosity-recent-activity">
                    <h2><?php _e('Recent Activity', 'erosity'); ?></h2>
                    <?php self::render_recent_activity(); ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render recent activity
     */
    private static function render_recent_activity() {
        $recent_bookings = get_posts(array(
            'post_type' => 'erosity_booking',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        
        if (empty($recent_bookings)) {
            echo '<p>' . __('No recent activity.', 'erosity') . '</p>';
            return;
        }
        
        echo '<ul class="erosity-activity-list">';
        foreach ($recent_bookings as $booking) {
            echo '<li>';
            echo '<strong>' . esc_html($booking->post_title) . '</strong> - ';
            echo esc_html(get_the_date('', $booking->ID));
            echo '</li>';
        }
        echo '</ul>';
    }
}
