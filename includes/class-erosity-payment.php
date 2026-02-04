<?php
/**
 * Payment Management (Stripe Integration)
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Payment Class
 */
class Erosity_Payment {
    
    /**
     * Initialize
     */
    public static function init() {
        // Payment initialization will be added here
    }
    
    /**
     * Get Stripe API key
     *
     * @return string
     */
    public static function get_stripe_api_key() {
        $settings = get_option('erosity_settings', array());
        $mode = isset($settings['stripe_mode']) ? $settings['stripe_mode'] : 'test';
        
        if ($mode === 'live') {
            return isset($settings['stripe_live_secret_key']) ? $settings['stripe_live_secret_key'] : '';
        }
        
        return isset($settings['stripe_test_secret_key']) ? $settings['stripe_test_secret_key'] : '';
    }
    
    /**
     * Calculate commission
     *
     * @param float $amount Booking amount
     * @param float $rate   Commission rate (percentage)
     * @return float
     */
    public static function calculate_commission($amount, $rate = null) {
        if (is_null($rate)) {
            $settings = get_option('erosity_settings', array());
            $rate = isset($settings['commission_rate']) ? floatval($settings['commission_rate']) : 10;
        }
        
        return round(($amount * $rate) / 100, 2);
    }
    
    /**
     * Process split payment
     *
     * @param int   $booking_id Booking ID
     * @param float $amount     Total amount
     * @param int   $vendor_id  Vendor user ID
     * @return array|WP_Error
     */
    public static function process_split_payment($booking_id, $amount, $vendor_id) {
        // This will integrate with Stripe Connect
        // For now, return a placeholder
        
        $commission = self::calculate_commission($amount);
        $vendor_amount = $amount - $commission;
        
        return array(
            'success' => true,
            'amount' => $amount,
            'commission' => $commission,
            'vendor_amount' => $vendor_amount,
        );
    }
    
    /**
     * Create authorization hold for deposit
     *
     * @param float  $amount     Deposit amount
     * @param string $payment_id Payment ID
     * @return array|WP_Error
     */
    public static function create_authorization_hold($amount, $payment_id) {
        // Stripe authorization hold implementation
        return array(
            'success' => true,
            'hold_id' => 'hold_' . uniqid(),
            'amount' => $amount,
        );
    }
    
    /**
     * Capture deposit
     *
     * @param string $hold_id  Hold ID
     * @param float  $amount   Amount to capture
     * @return array|WP_Error
     */
    public static function capture_deposit($hold_id, $amount = null) {
        // Capture the authorized amount
        return array('success' => true);
    }
    
    /**
     * Release authorization hold
     *
     * @param string $hold_id Hold ID
     * @return array|WP_Error
     */
    public static function release_hold($hold_id) {
        // Release the hold
        return array('success' => true);
    }
    
    /**
     * Process refund
     *
     * @param int   $booking_id Booking ID
     * @param float $amount     Refund amount
     * @return array|WP_Error
     */
    public static function process_refund($booking_id, $amount) {
        // Process refund through Stripe
        return array(
            'success' => true,
            'refund_id' => 'refund_' . uniqid(),
            'amount' => $amount,
        );
    }
}
