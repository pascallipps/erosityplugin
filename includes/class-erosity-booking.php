<?php
/**
 * Booking Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Booking Class
 */
class Erosity_Booking {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
    }
    
    /**
     * Add meta boxes
     */
    public static function add_meta_boxes() {
        add_meta_box(
            'erosity_booking_details',
            __('Booking Details', 'erosity'),
            array(__CLASS__, 'render_details_meta_box'),
            'erosity_booking',
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
        echo '<p><strong>' . __('Booking details will be displayed here', 'erosity') . '</strong></p>';
    }
    
    /**
     * Create booking
     *
     * @param array $data Booking data
     * @return int|WP_Error
     */
    public static function create_booking($data) {
        $booking_data = array(
            'post_type' => 'erosity_booking',
            'post_status' => 'pending',
            'post_title' => sprintf(__('Booking #%s', 'erosity'), uniqid()),
        );
        
        $booking_id = wp_insert_post($booking_data);
        
        if (is_wp_error($booking_id)) {
            return $booking_id;
        }
        
        // Save booking meta data
        foreach ($data as $key => $value) {
            update_post_meta($booking_id, '_' . $key, $value);
        }
        
        return $booking_id;
    }
    
    /**
     * Get booking status
     *
     * @param int $booking_id Booking ID
     * @return string
     */
    public static function get_status($booking_id) {
        return get_post_meta($booking_id, '_booking_status', true) ?: 'pending';
    }
    
    /**
     * Update booking status
     *
     * @param int    $booking_id Booking ID
     * @param string $status     New status
     * @return bool
     */
    public static function update_status($booking_id, $status) {
        return update_post_meta($booking_id, '_booking_status', $status);
    }
}
