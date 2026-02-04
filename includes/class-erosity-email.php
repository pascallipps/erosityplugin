<?php
/**
 * Email Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Email Class
 */
class Erosity_Email {
    
    /**
     * Initialize
     */
    public static function init() {
        add_filter('wp_mail_from', array(__CLASS__, 'set_from_email'));
        add_filter('wp_mail_from_name', array(__CLASS__, 'set_from_name'));
    }
    
    /**
     * Set from email
     *
     * @param string $email Email address
     * @return string
     */
    public static function set_from_email($email) {
        $settings = get_option('erosity_settings', array());
        return isset($settings['email_from_address']) ? $settings['email_from_address'] : $email;
    }
    
    /**
     * Set from name
     *
     * @param string $name From name
     * @return string
     */
    public static function set_from_name($name) {
        $settings = get_option('erosity_settings', array());
        return isset($settings['email_from_name']) ? $settings['email_from_name'] : $name;
    }
    
    /**
     * Send booking confirmation email
     *
     * @param int $booking_id Booking ID
     * @return bool
     */
    public static function send_booking_confirmation($booking_id) {
        $booking = get_post($booking_id);
        $guest_id = get_post_meta($booking_id, '_guest_id', true);
        $guest = get_userdata($guest_id);
        
        $subject = __('Booking Confirmation', 'erosity');
        $message = sprintf(
            __('Your booking #%s has been confirmed.', 'erosity'),
            $booking_id
        );
        
        return wp_mail($guest->user_email, $subject, $message);
    }
    
    /**
     * Send reminder email
     *
     * @param int $booking_id Booking ID
     * @param int $days_before Days before checkin
     * @return bool
     */
    public static function send_reminder_email($booking_id, $days_before) {
        $booking = get_post($booking_id);
        $guest_id = get_post_meta($booking_id, '_guest_id', true);
        $guest = get_userdata($guest_id);
        
        $subject = sprintf(__('Reminder: Your booking is in %d days', 'erosity'), $days_before);
        $message = sprintf(
            __('This is a reminder that your booking #%s is coming up in %d days.', 'erosity'),
            $booking_id,
            $days_before
        );
        
        return wp_mail($guest->user_email, $subject, $message);
    }
    
    /**
     * Send review request email
     *
     * @param int $booking_id Booking ID
     * @return bool
     */
    public static function send_review_request($booking_id) {
        $booking = get_post($booking_id);
        $guest_id = get_post_meta($booking_id, '_guest_id', true);
        $guest = get_userdata($guest_id);
        
        $subject = __('How was your stay? Leave a review', 'erosity');
        $message = sprintf(
            __('Thank you for your booking #%s. We would love to hear about your experience!', 'erosity'),
            $booking_id
        );
        
        return wp_mail($guest->user_email, $subject, $message);
    }
    
    /**
     * Send custom email based on trigger
     *
     * @param int    $booking_id Booking ID
     * @param string $trigger    Trigger action
     * @return bool
     */
    public static function send_custom_email($booking_id, $trigger) {
        global $wpdb;
        
        $property_id = get_post_meta($booking_id, '_property_id', true);
        
        // Get custom email template
        $template = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}erosity_email_templates 
            WHERE property_id = %d AND trigger_action = %s AND is_active = 1",
            $property_id,
            $trigger
        ));
        
        if (!$template) {
            return false;
        }
        
        $guest_id = get_post_meta($booking_id, '_guest_id', true);
        $guest = get_userdata($guest_id);
        
        // Replace placeholders in subject and message
        $subject = self::replace_placeholders($template->subject, $booking_id);
        $message = self::replace_placeholders($template->message, $booking_id);
        
        return wp_mail($guest->user_email, $subject, $message);
    }
    
    /**
     * Replace placeholders in email content
     *
     * @param string $content    Email content
     * @param int    $booking_id Booking ID
     * @return string
     */
    private static function replace_placeholders($content, $booking_id) {
        $guest_id = get_post_meta($booking_id, '_guest_id', true);
        $guest = get_userdata($guest_id);
        $property_id = get_post_meta($booking_id, '_property_id', true);
        $property = get_post($property_id);
        
        $replacements = array(
            '{guest_name}' => $guest->display_name,
            '{property_name}' => $property->post_title,
            '{booking_id}' => $booking_id,
            '{checkin_date}' => get_post_meta($booking_id, '_checkin_date', true),
            '{checkout_date}' => get_post_meta($booking_id, '_checkout_date', true),
        );
        
        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
