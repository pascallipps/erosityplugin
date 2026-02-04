<?php
/**
 * Coupon Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Coupon Class
 */
class Erosity_Coupon {
    
    /**
     * Initialize
     */
    public static function init() {
        // Coupon initialization
    }
    
    /**
     * Create coupon
     *
     * @param array $data Coupon data
     * @return int|false
     */
    public static function create_coupon($data) {
        global $wpdb;
        
        $defaults = array(
            'code' => '',
            'type' => 'vendor', // vendor or global
            'discount_type' => 'percent', // percent or fixed
            'discount_value' => 0,
            'property_id' => null,
            'property_type' => null,
            'vendor_id' => null,
            'usage_limit' => null,
            'valid_from' => null,
            'valid_until' => null,
            'is_active' => 1,
            'created_by' => get_current_user_id(),
        );
        
        $data = wp_parse_args($data, $defaults);
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'erosity_coupons',
            $data,
            array('%s', '%s', '%s', '%f', '%d', '%s', '%d', '%d', '%s', '%s', '%d', '%d')
        );
        
        return $result ? $wpdb->insert_id : false;
    }
    
    /**
     * Get coupon by code
     *
     * @param string $code Coupon code
     * @return object|null
     */
    public static function get_coupon($code) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}erosity_coupons WHERE code = %s AND is_active = 1",
            $code
        ));
    }
    
    /**
     * Validate coupon
     *
     * @param string $code        Coupon code
     * @param int    $property_id Property ID (optional)
     * @param string $property_type Property type (optional)
     * @return array Array with 'valid' boolean and 'message' string
     */
    public static function validate_coupon($code, $property_id = null, $property_type = null) {
        $coupon = self::get_coupon($code);
        
        if (!$coupon) {
            return array(
                'valid' => false,
                'message' => __('Invalid coupon code.', 'erosity'),
            );
        }
        
        // Check usage limit
        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            return array(
                'valid' => false,
                'message' => __('This coupon has reached its usage limit.', 'erosity'),
            );
        }
        
        // Check validity dates
        $now = current_time('mysql');
        if ($coupon->valid_from && $now < $coupon->valid_from) {
            return array(
                'valid' => false,
                'message' => __('This coupon is not yet valid.', 'erosity'),
            );
        }
        
        if ($coupon->valid_until && $now > $coupon->valid_until) {
            return array(
                'valid' => false,
                'message' => __('This coupon has expired.', 'erosity'),
            );
        }
        
        // Check property-specific coupons
        if ($coupon->property_id && $property_id && $coupon->property_id != $property_id) {
            return array(
                'valid' => false,
                'message' => __('This coupon is not valid for this property.', 'erosity'),
            );
        }
        
        return array(
            'valid' => true,
            'coupon' => $coupon,
            'message' => __('Coupon applied successfully.', 'erosity'),
        );
    }
    
    /**
     * Calculate discount
     *
     * @param object $coupon Coupon object
     * @param float  $amount Original amount
     * @return float
     */
    public static function calculate_discount($coupon, $amount) {
        if ($coupon->discount_type === 'percent') {
            return round(($amount * $coupon->discount_value) / 100, 2);
        }
        
        return min($coupon->discount_value, $amount);
    }
    
    /**
     * Apply coupon to booking
     *
     * @param int    $booking_id Booking ID
     * @param string $code       Coupon code
     * @return array|WP_Error
     */
    public static function apply_coupon($booking_id, $code) {
        $property_id = get_post_meta($booking_id, '_property_id', true);
        $property_type = get_post_meta($booking_id, '_property_type', true);
        
        $validation = self::validate_coupon($code, $property_id, $property_type);
        
        if (!$validation['valid']) {
            return new WP_Error('invalid_coupon', $validation['message']);
        }
        
        $coupon = $validation['coupon'];
        $original_amount = floatval(get_post_meta($booking_id, '_original_amount', true));
        $discount = self::calculate_discount($coupon, $original_amount);
        $final_amount = $original_amount - $discount;
        
        // Save coupon info to booking
        update_post_meta($booking_id, '_coupon_id', $coupon->id);
        update_post_meta($booking_id, '_coupon_code', $code);
        update_post_meta($booking_id, '_discount_amount', $discount);
        update_post_meta($booking_id, '_final_amount', $final_amount);
        
        // Increment usage count
        global $wpdb;
        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->prefix}erosity_coupons SET usage_count = usage_count + 1 WHERE id = %d",
            $coupon->id
        ));
        
        return array(
            'success' => true,
            'discount' => $discount,
            'final_amount' => $final_amount,
            'coupon_type' => $coupon->type,
        );
    }
    
    /**
     * Get vendor coupons
     *
     * @param int $vendor_id Vendor user ID
     * @return array
     */
    public static function get_vendor_coupons($vendor_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}erosity_coupons WHERE vendor_id = %d OR created_by = %d",
            $vendor_id,
            $vendor_id
        ));
    }
}
