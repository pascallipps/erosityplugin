<?php
/**
 * User Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity User Class
 */
class Erosity_User {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('user_register', array(__CLASS__, 'on_user_register'));
        add_action('profile_update', array(__CLASS__, 'on_profile_update'));
    }
    
    /**
     * On user registration
     *
     * @param int $user_id User ID
     */
    public static function on_user_register($user_id) {
        global $wpdb;
        
        // Create user data entry
        $wpdb->insert(
            $wpdb->prefix . 'erosity_user_data',
            array(
                'user_id' => $user_id,
                'age_verified' => 0
            ),
            array('%d', '%d')
        );
    }
    
    /**
     * On profile update
     *
     * @param int $user_id User ID
     */
    public static function on_profile_update($user_id) {
        // Handle profile updates if needed
    }
    
    /**
     * Get user extended data
     *
     * @param int $user_id User ID
     * @return object|null
     */
    public static function get_user_data($user_id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}erosity_user_data WHERE user_id = %d",
            $user_id
        ));
    }
    
    /**
     * Update user extended data
     *
     * @param int   $user_id User ID
     * @param array $data    Data to update
     * @return bool
     */
    public static function update_user_data($user_id, $data) {
        global $wpdb;
        
        $existing = self::get_user_data($user_id);
        
        if ($existing) {
            return $wpdb->update(
                $wpdb->prefix . 'erosity_user_data',
                $data,
                array('user_id' => $user_id),
                null,
                array('%d')
            );
        } else {
            $data['user_id'] = $user_id;
            return $wpdb->insert(
                $wpdb->prefix . 'erosity_user_data',
                $data
            );
        }
    }
    
    /**
     * Check if user has completed profile
     *
     * @param int $user_id User ID
     * @return bool
     */
    public static function has_complete_profile($user_id) {
        $user_data = self::get_user_data($user_id);
        
        if (!$user_data) {
            return false;
        }
        
        // Check required fields
        $required_fields = array('street', 'house_number', 'postal_code', 'city', 'country');
        
        foreach ($required_fields as $field) {
            if (empty($user_data->$field)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check if user is age verified
     *
     * @param int $user_id User ID
     * @return bool
     */
    public static function is_age_verified($user_id) {
        $user_data = self::get_user_data($user_id);
        
        return $user_data && $user_data->age_verified == 1;
    }
}
