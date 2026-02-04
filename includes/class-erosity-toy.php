<?php
/**
 * Toy Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Toy Class
 */
class Erosity_Toy {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post_erosity_toy', array(__CLASS__, 'save_meta_data'));
    }
    
    /**
     * Add meta boxes
     */
    public static function add_meta_boxes() {
        add_meta_box(
            'erosity_toy_details',
            __('Toy Details', 'erosity'),
            array(__CLASS__, 'render_details_meta_box'),
            'erosity_toy',
            'normal',
            'high'
        );
        
        add_meta_box(
            'erosity_toy_shipping',
            __('Shipping Settings', 'erosity'),
            array(__CLASS__, 'render_shipping_meta_box'),
            'erosity_toy',
            'side',
            'default'
        );
    }
    
    /**
     * Render details meta box
     *
     * @param WP_Post $post Post object
     */
    public static function render_details_meta_box($post) {
        wp_nonce_field('erosity_toy_meta', 'erosity_toy_nonce');
        echo '<p><strong>' . __('Toy-specific fields will be added here', 'erosity') . '</strong></p>';
    }
    
    /**
     * Render shipping meta box
     *
     * @param WP_Post $post Post object
     */
    public static function render_shipping_meta_box($post) {
        $shipping_buffer = get_post_meta($post->ID, '_shipping_buffer_days', true) ?: 2;
        $shipping_cost = get_post_meta($post->ID, '_shipping_cost', true) ?: 0;
        $return_cost = get_post_meta($post->ID, '_return_shipping_cost', true) ?: 0;
        
        echo '<p>';
        echo '<label>' . __('Shipping Buffer Days:', 'erosity') . '</label><br>';
        echo '<input type="number" name="shipping_buffer_days" value="' . esc_attr($shipping_buffer) . '" min="0" style="width:100%">';
        echo '</p>';
        
        echo '<p>';
        echo '<label>' . __('Shipping Cost (€):', 'erosity') . '</label><br>';
        echo '<input type="number" name="shipping_cost" value="' . esc_attr($shipping_cost) . '" min="0" step="0.01" style="width:100%">';
        echo '</p>';
        
        echo '<p>';
        echo '<label>' . __('Return Shipping Cost (€):', 'erosity') . '</label><br>';
        echo '<input type="number" name="return_shipping_cost" value="' . esc_attr($return_cost) . '" min="0" step="0.01" style="width:100%">';
        echo '</p>';
    }
    
    /**
     * Save meta data
     *
     * @param int $post_id Post ID
     */
    public static function save_meta_data($post_id) {
        if (!isset($_POST['erosity_toy_nonce']) || 
            !wp_verify_nonce($_POST['erosity_toy_nonce'], 'erosity_toy_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save shipping settings
        if (isset($_POST['shipping_buffer_days'])) {
            update_post_meta($post_id, '_shipping_buffer_days', absint($_POST['shipping_buffer_days']));
        }
        
        if (isset($_POST['shipping_cost'])) {
            update_post_meta($post_id, '_shipping_cost', floatval($_POST['shipping_cost']));
        }
        
        if (isset($_POST['return_shipping_cost'])) {
            update_post_meta($post_id, '_return_shipping_cost', floatval($_POST['return_shipping_cost']));
        }
    }
}
