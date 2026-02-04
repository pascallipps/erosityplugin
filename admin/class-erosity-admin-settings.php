<?php
/**
 * Admin Settings
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Admin Settings Class
 */
class Erosity_Admin_Settings {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }
    
    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('erosity_settings', 'erosity_settings', array(__CLASS__, 'sanitize_settings'));
    }
    
    /**
     * Sanitize settings
     *
     * @param array $input Settings input
     * @return array
     */
    public static function sanitize_settings($input) {
        $sanitized = array();
        
        // Commission rate
        if (isset($input['commission_rate'])) {
            $sanitized['commission_rate'] = floatval($input['commission_rate']);
        }
        
        // Currency
        if (isset($input['currency'])) {
            $sanitized['currency'] = sanitize_text_field($input['currency']);
        }
        
        // Stripe settings
        $stripe_fields = array(
            'stripe_mode',
            'stripe_test_publishable_key',
            'stripe_test_secret_key',
            'stripe_live_publishable_key',
            'stripe_live_secret_key',
        );
        
        foreach ($stripe_fields as $field) {
            if (isset($input[$field])) {
                $sanitized[$field] = sanitize_text_field($input[$field]);
            }
        }
        
        // Age verification
        if (isset($input['age_verification_required'])) {
            $sanitized['age_verification_required'] = (bool) $input['age_verification_required'];
        }
        
        if (isset($input['min_age'])) {
            $sanitized['min_age'] = absint($input['min_age']);
        }
        
        // Email settings
        if (isset($input['email_from_name'])) {
            $sanitized['email_from_name'] = sanitize_text_field($input['email_from_name']);
        }
        
        if (isset($input['email_from_address'])) {
            $sanitized['email_from_address'] = sanitize_email($input['email_from_address']);
        }
        
        // Map settings
        if (isset($input['map_provider'])) {
            $sanitized['map_provider'] = sanitize_text_field($input['map_provider']);
        }
        
        if (isset($input['google_maps_api_key'])) {
            $sanitized['google_maps_api_key'] = sanitize_text_field($input['google_maps_api_key']);
        }
        
        return $sanitized;
    }
    
    /**
     * Render settings page
     */
    public static function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Save settings
        if (isset($_POST['erosity_settings_submit'])) {
            check_admin_referer('erosity_settings_nonce');
            
            $settings = get_option('erosity_settings', array());
            $new_settings = self::sanitize_settings($_POST['erosity_settings']);
            $settings = array_merge($settings, $new_settings);
            
            update_option('erosity_settings', $settings);
            
            echo '<div class="notice notice-success"><p>' . __('Settings saved.', 'erosity') . '</p></div>';
        }
        
        $settings = get_option('erosity_settings', array());
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('erosity_settings_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="commission_rate"><?php _e('Commission Rate (%)', 'erosity'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="commission_rate" 
                                   name="erosity_settings[commission_rate]" 
                                   value="<?php echo esc_attr($settings['commission_rate'] ?? 10); ?>" 
                                   step="0.01" 
                                   min="0" 
                                   max="100">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="currency"><?php _e('Currency', 'erosity'); ?></label>
                        </th>
                        <td>
                            <select id="currency" name="erosity_settings[currency]">
                                <option value="EUR" <?php selected($settings['currency'] ?? 'EUR', 'EUR'); ?>>EUR</option>
                                <option value="USD" <?php selected($settings['currency'] ?? 'EUR', 'USD'); ?>>USD</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="stripe_mode"><?php _e('Stripe Mode', 'erosity'); ?></label>
                        </th>
                        <td>
                            <select id="stripe_mode" name="erosity_settings[stripe_mode]">
                                <option value="test" <?php selected($settings['stripe_mode'] ?? 'test', 'test'); ?>><?php _e('Test', 'erosity'); ?></option>
                                <option value="live" <?php selected($settings['stripe_mode'] ?? 'test', 'live'); ?>><?php _e('Live', 'erosity'); ?></option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="age_verification_required"><?php _e('Age Verification', 'erosity'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="age_verification_required" 
                                   name="erosity_settings[age_verification_required]" 
                                   value="1" 
                                   <?php checked($settings['age_verification_required'] ?? true, true); ?>>
                            <label for="age_verification_required"><?php _e('Require age verification (18+)', 'erosity'); ?></label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="map_provider"><?php _e('Map Provider', 'erosity'); ?></label>
                        </th>
                        <td>
                            <select id="map_provider" name="erosity_settings[map_provider]">
                                <option value="openstreetmap" <?php selected($settings['map_provider'] ?? 'openstreetmap', 'openstreetmap'); ?>>OpenStreetMap</option>
                                <option value="google" <?php selected($settings['map_provider'] ?? 'openstreetmap', 'google'); ?>>Google Maps</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" 
                           name="erosity_settings_submit" 
                           class="button-primary" 
                           value="<?php esc_attr_e('Save Settings', 'erosity'); ?>">
                </p>
            </form>
        </div>
        <?php
    }
}
