<?php
/**
 * Property Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Property Class
 */
class Erosity_Property {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post_erosity_property', array(__CLASS__, 'save_meta_data'));
    }
    
    /**
     * Add meta boxes
     */
    public static function add_meta_boxes() {
        add_meta_box(
            'erosity_property_details',
            __('Property Details', 'erosity'),
            array(__CLASS__, 'render_details_meta_box'),
            'erosity_property',
            'normal',
            'high'
        );
    }
    
    /**
     * Render details meta box
     *
     * @param WP_Post $post Post object
     */
    public static function render_details_meta_box($post) {
        wp_nonce_field('erosity_property_meta', 'erosity_property_nonce');
        
        // Get saved meta data
        $meta = get_post_meta($post->ID);
        
        echo '<p><strong>' . __('This meta box will contain property-specific fields', 'erosity') . '</strong></p>';
    }
    
    /**
     * Save meta data
     *
     * @param int $post_id Post ID
     */
    public static function save_meta_data($post_id) {
        // Security checks
        if (!isset($_POST['erosity_property_nonce']) || 
            !wp_verify_nonce($_POST['erosity_property_nonce'], 'erosity_property_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save meta data here
    }
    
    /**
     * Get property status
     *
     * @param int $property_id Property ID
     * @return string
     */
    public static function get_status($property_id) {
        return get_post_meta($property_id, '_erosity_status', true) ?: 'draft';
    }
}
