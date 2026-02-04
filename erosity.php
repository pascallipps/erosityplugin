<?php
/**
 * Plugin Name: Erosity
 * Plugin URI: https://erosity.com
 * Description: A comprehensive platform for renting erotic vacation properties and toys with commission-based business model
 * Version: 1.0.0
 * Author: Erosity Team
 * Author URI: https://erosity.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: erosity
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EROSITY_VERSION', '1.0.0');
define('EROSITY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EROSITY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EROSITY_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Erosity Plugin Class
 */
final class Erosity {
    
    /**
     * Plugin instance
     *
     * @var Erosity
     */
    private static $instance = null;
    
    /**
     * Get plugin instance
     *
     * @return Erosity
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Core includes
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-post-types.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-taxonomies.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-database.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-user.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-property.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-toy.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-booking.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-review.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-message.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-payment.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-email.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-calendar.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-geocoding.php';
        require_once EROSITY_PLUGIN_DIR . 'includes/class-erosity-coupon.php';
        
        // Admin includes
        if (is_admin()) {
            require_once EROSITY_PLUGIN_DIR . 'admin/class-erosity-admin.php';
            require_once EROSITY_PLUGIN_DIR . 'admin/class-erosity-admin-settings.php';
            require_once EROSITY_PLUGIN_DIR . 'admin/class-erosity-admin-statistics.php';
            require_once EROSITY_PLUGIN_DIR . 'admin/class-erosity-admin-support.php';
        }
        
        // Public includes
        require_once EROSITY_PLUGIN_DIR . 'public/class-erosity-public.php';
        require_once EROSITY_PLUGIN_DIR . 'public/class-erosity-frontend-forms.php';
        require_once EROSITY_PLUGIN_DIR . 'public/class-erosity-frontend-auth.php';
        require_once EROSITY_PLUGIN_DIR . 'public/class-erosity-frontend-dashboard.php';
        require_once EROSITY_PLUGIN_DIR . 'public/class-erosity-frontend-listing.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('init', array($this, 'init'), 0);
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Register post types and taxonomies
        Erosity_Post_Types::register_post_types();
        Erosity_Taxonomies::register_taxonomies();
        
        // Create database tables
        Erosity_Database::create_tables();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set default options
        $this->set_default_options();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain('erosity', false, dirname(EROSITY_PLUGIN_BASENAME) . '/languages');
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize post types
        Erosity_Post_Types::init();
        
        // Initialize taxonomies
        Erosity_Taxonomies::init();
        
        // Initialize user management
        Erosity_User::init();
        
        // Initialize admin
        if (is_admin()) {
            Erosity_Admin::init();
        }
        
        // Initialize public
        Erosity_Public::init();
    }
    
    /**
     * Set default plugin options
     */
    private function set_default_options() {
        $default_options = array(
            'commission_rate' => 10, // 10% default commission
            'currency' => 'EUR',
            'stripe_mode' => 'test',
            'stripe_test_publishable_key' => '',
            'stripe_test_secret_key' => '',
            'stripe_live_publishable_key' => '',
            'stripe_live_secret_key' => '',
            'age_verification_required' => true,
            'min_age' => 18,
            'default_cancellation_policy' => array(
                array('days' => 30, 'refund' => 100),
                array('days' => 7, 'refund' => 50),
                array('days' => 0, 'refund' => 0)
            ),
            'email_from_name' => get_bloginfo('name'),
            'email_from_address' => get_bloginfo('admin_email'),
            'map_provider' => 'openstreetmap', // or 'google'
            'google_maps_api_key' => '',
        );
        
        add_option('erosity_settings', $default_options);
    }
}

/**
 * Get the main plugin instance
 *
 * @return Erosity
 */
function erosity() {
    return Erosity::instance();
}

// Initialize the plugin
erosity();
