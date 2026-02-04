<?php
/**
 * Message Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Message Class
 */
class Erosity_Message {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_filter('the_content', array(__CLASS__, 'filter_sensitive_info'));
    }
    
    /**
     * Add meta boxes
     */
    public static function add_meta_boxes() {
        add_meta_box(
            'erosity_message_details',
            __('Message Details', 'erosity'),
            array(__CLASS__, 'render_details_meta_box'),
            'erosity_message',
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
        $sender_id = get_post_meta($post->ID, '_sender_id', true);
        $receiver_id = get_post_meta($post->ID, '_receiver_id', true);
        $property_id = get_post_meta($post->ID, '_property_id', true);
        
        echo '<p><strong>' . __('Sender:', 'erosity') . '</strong> ' . get_userdata($sender_id)->display_name . '</p>';
        echo '<p><strong>' . __('Receiver:', 'erosity') . '</strong> ' . get_userdata($receiver_id)->display_name . '</p>';
        if ($property_id) {
            echo '<p><strong>' . __('Property:', 'erosity') . '</strong> ' . get_the_title($property_id) . '</p>';
        }
    }
    
    /**
     * Filter sensitive information from messages
     *
     * @param string $content Message content
     * @return string
     */
    public static function filter_sensitive_info($content) {
        if (get_post_type() !== 'erosity_message') {
            return $content;
        }
        
        // Detect email addresses
        $email_pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        if (preg_match($email_pattern, $content)) {
            $content .= '<div class="erosity-warning">' . __('⚠️ Warning: This message contains an email address. For data protection reasons, please consider whether you want to share this information.', 'erosity') . '</div>';
        }
        
        // Detect phone numbers
        $phone_pattern = '/(\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/';
        if (preg_match($phone_pattern, $content)) {
            $content .= '<div class="erosity-warning">' . __('⚠️ Warning: This message contains a phone number. For data protection reasons, please consider whether you want to share this information.', 'erosity') . '</div>';
        }
        
        return $content;
    }
    
    /**
     * Send message
     *
     * @param int    $sender_id   Sender user ID
     * @param int    $receiver_id Receiver user ID
     * @param string $message     Message content
     * @param int    $property_id Property ID (optional)
     * @return int|WP_Error
     */
    public static function send_message($sender_id, $receiver_id, $message, $property_id = 0) {
        $message_data = array(
            'post_type' => 'erosity_message',
            'post_status' => 'private',
            'post_title' => sprintf(__('Message from %s to %s', 'erosity'), 
                get_userdata($sender_id)->display_name,
                get_userdata($receiver_id)->display_name
            ),
            'post_content' => $message,
            'post_author' => $sender_id,
        );
        
        $message_id = wp_insert_post($message_data);
        
        if (is_wp_error($message_id)) {
            return $message_id;
        }
        
        update_post_meta($message_id, '_sender_id', $sender_id);
        update_post_meta($message_id, '_receiver_id', $receiver_id);
        update_post_meta($message_id, '_property_id', $property_id);
        update_post_meta($message_id, '_is_read', 0);
        
        return $message_id;
    }
    
    /**
     * Mark message as read
     *
     * @param int $message_id Message ID
     * @return bool
     */
    public static function mark_as_read($message_id) {
        return update_post_meta($message_id, '_is_read', 1);
    }
}
