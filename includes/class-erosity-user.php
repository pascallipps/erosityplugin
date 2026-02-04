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
        add_action('show_user_profile', array(__CLASS__, 'show_commission_rate_field'));
        add_action('edit_user_profile', array(__CLASS__, 'show_commission_rate_field'));
        add_action('personal_options_update', array(__CLASS__, 'save_commission_rate_field'));
        add_action('edit_user_profile_update', array(__CLASS__, 'save_commission_rate_field'));
        add_filter('manage_users_columns', array(__CLASS__, 'add_commission_rate_column'));
        add_filter('manage_users_custom_column', array(__CLASS__, 'show_commission_rate_column'), 10, 3);
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
    
    /**
     * Get user commission rate
     * Returns user-specific rate or global rate if not set
     *
     * @param int $user_id User ID
     * @return float Commission rate percentage
     */
    public static function get_commission_rate($user_id) {
        $user_data = self::get_user_data($user_id);
        
        // If user has a custom commission rate, use it
        if ($user_data && !is_null($user_data->commission_rate)) {
            return floatval($user_data->commission_rate);
        }
        
        // Otherwise use global rate
        $settings = get_option('erosity_settings', array());
        return isset($settings['commission_rate']) ? floatval($settings['commission_rate']) : 10;
    }
    
    /**
     * Set user commission rate
     *
     * @param int   $user_id User ID
     * @param float $rate    Commission rate (null to use global)
     * @return bool
     */
    public static function set_commission_rate($user_id, $rate) {
        return self::update_user_data($user_id, array(
            'commission_rate' => is_null($rate) ? null : floatval($rate)
        ));
    }
    
    /**
     * Show commission rate field in user profile
     *
     * @param WP_User $user User object
     */
    public static function show_commission_rate_field($user) {
        if (!current_user_can('edit_users')) {
            return;
        }
        
        $user_data = self::get_user_data($user->ID);
        $commission_rate = $user_data && !is_null($user_data->commission_rate) ? $user_data->commission_rate : '';
        
        $settings = get_option('erosity_settings', array());
        $global_rate = isset($settings['commission_rate']) ? floatval($settings['commission_rate']) : 10;
        
        ?>
        <h3><?php _e('Erosity Settings', 'erosity'); ?></h3>
        <table class="form-table">
            <tr>
                <th>
                    <label for="erosity_commission_rate"><?php _e('Custom Commission Rate (%)', 'erosity'); ?></label>
                </th>
                <td>
                    <input type="number" 
                           name="erosity_commission_rate" 
                           id="erosity_commission_rate" 
                           value="<?php echo esc_attr($commission_rate); ?>" 
                           step="0.01" 
                           min="0" 
                           max="100" 
                           class="regular-text">
                    <p class="description">
                        <?php printf(
                            __('Leave empty to use the global commission rate (%s%%). Set a custom rate to override the global setting for this user.', 'erosity'),
                            $global_rate
                        ); ?>
                    </p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save commission rate field from user profile
     *
     * @param int $user_id User ID
     */
    public static function save_commission_rate_field($user_id) {
        if (!current_user_can('edit_users')) {
            return;
        }
        
        $rate = isset($_POST['erosity_commission_rate']) && $_POST['erosity_commission_rate'] !== '' 
            ? floatval($_POST['erosity_commission_rate']) 
            : null;
        
        self::set_commission_rate($user_id, $rate);
    }
    
    /**
     * Add commission rate column to users list
     *
     * @param array $columns Existing columns
     * @return array Modified columns
     */
    public static function add_commission_rate_column($columns) {
        $columns['erosity_commission'] = __('Commission Rate (%)', 'erosity');
        return $columns;
    }
    
    /**
     * Show commission rate in users list column
     *
     * @param string $value       Column value
     * @param string $column_name Column name
     * @param int    $user_id     User ID
     * @return string Column content
     */
    public static function show_commission_rate_column($value, $column_name, $user_id) {
        if ($column_name === 'erosity_commission') {
            $user_data = self::get_user_data($user_id);
            
            if ($user_data && !is_null($user_data->commission_rate)) {
                return sprintf(
                    '<strong>%s%%</strong> <span class="description">(%s)</span>',
                    number_format($user_data->commission_rate, 2),
                    __('Custom', 'erosity')
                );
            } else {
                $settings = get_option('erosity_settings', array());
                $global_rate = isset($settings['commission_rate']) ? floatval($settings['commission_rate']) : 10;
                return sprintf(
                    '%s%% <span class="description">(%s)</span>',
                    number_format($global_rate, 2),
                    __('Global', 'erosity')
                );
            }
        }
        
        return $value;
    }
}
