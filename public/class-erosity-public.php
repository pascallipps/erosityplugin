<?php
/**
 * Public functionality
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Public Class
 */
class Erosity_Public {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
        add_action('init', array(__CLASS__, 'register_shortcodes'));
        
        // Initialize sub-classes
        Erosity_Frontend_Auth::init();
        Erosity_Frontend_Dashboard::init();
        Erosity_Frontend_Forms::init();
        Erosity_Frontend_Listing::init();
    }
    
    /**
     * Enqueue public scripts and styles
     */
    public static function enqueue_scripts() {
        wp_enqueue_style(
            'erosity-public',
            EROSITY_PLUGIN_URL . 'assets/css/public.css',
            array(),
            EROSITY_VERSION
        );
        
        wp_enqueue_script(
            'erosity-public',
            EROSITY_PLUGIN_URL . 'assets/js/public.js',
            array('jquery'),
            EROSITY_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('erosity-public', 'erosityData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('erosity_nonce'),
            'strings' => array(
                'loading' => __('Loading...', 'erosity'),
                'error' => __('An error occurred', 'erosity'),
            ),
        ));
    }
    
    /**
     * Register shortcodes
     */
    public static function register_shortcodes() {
        add_shortcode('erosity_login', array('Erosity_Frontend_Auth', 'render_login_form'));
        add_shortcode('erosity_register', array('Erosity_Frontend_Auth', 'render_register_form'));
        add_shortcode('erosity_dashboard', array('Erosity_Frontend_Dashboard', 'render_dashboard'));
        add_shortcode('erosity_property_form', array('Erosity_Frontend_Forms', 'render_property_form'));
        add_shortcode('erosity_toy_form', array('Erosity_Frontend_Forms', 'render_toy_form'));
        add_shortcode('erosity_properties', array('Erosity_Frontend_Listing', 'render_property_list'));
        add_shortcode('erosity_toys', array('Erosity_Frontend_Listing', 'render_toy_list'));
        add_shortcode('erosity_map', array('Erosity_Frontend_Listing', 'render_map'));
    }
}
