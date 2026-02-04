<?php
/**
 * Frontend Dashboard
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Frontend Dashboard Class
 */
class Erosity_Frontend_Dashboard {
    
    /**
     * Initialize
     */
    public static function init() {
        // Dashboard initialization
    }
    
    /**
     * Render dashboard
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_dashboard($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to access your dashboard.', 'erosity') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $user_data = Erosity_User::get_user_data($user_id);
        
        ob_start();
        ?>
        <div class="erosity-dashboard">
            <h1><?php _e('Dashboard', 'erosity'); ?></h1>
            
            <div class="erosity-dashboard-tabs">
                <ul class="erosity-tabs">
                    <li class="active"><a href="#overview"><?php _e('Overview', 'erosity'); ?></a></li>
                    <li><a href="#properties"><?php _e('My Properties', 'erosity'); ?></a></li>
                    <li><a href="#bookings"><?php _e('My Bookings', 'erosity'); ?></a></li>
                    <li><a href="#messages"><?php _e('Messages', 'erosity'); ?></a></li>
                    <li><a href="#profile"><?php _e('Profile', 'erosity'); ?></a></li>
                </ul>
                
                <div id="overview" class="erosity-tab-content active">
                    <?php self::render_overview_tab($user_id); ?>
                </div>
                
                <div id="properties" class="erosity-tab-content">
                    <?php self::render_properties_tab($user_id); ?>
                </div>
                
                <div id="bookings" class="erosity-tab-content">
                    <?php self::render_bookings_tab($user_id); ?>
                </div>
                
                <div id="messages" class="erosity-tab-content">
                    <?php self::render_messages_tab($user_id); ?>
                </div>
                
                <div id="profile" class="erosity-tab-content">
                    <?php self::render_profile_tab($user_id); ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render overview tab
     *
     * @param int $user_id User ID
     */
    private static function render_overview_tab($user_id) {
        $properties = get_posts(array(
            'post_type' => array('erosity_property', 'erosity_toy'),
            'author' => $user_id,
            'posts_per_page' => -1,
        ));
        
        $bookings = get_posts(array(
            'post_type' => 'erosity_booking',
            'meta_query' => array(
                array(
                    'key' => '_guest_id',
                    'value' => $user_id,
                ),
            ),
            'posts_per_page' => -1,
        ));
        
        ?>
        <h2><?php _e('Welcome back!', 'erosity'); ?></h2>
        
        <div class="erosity-stats-grid">
            <div class="erosity-stat-card">
                <h3><?php _e('My Listings', 'erosity'); ?></h3>
                <div class="stat-value"><?php echo count($properties); ?></div>
            </div>
            
            <div class="erosity-stat-card">
                <h3><?php _e('My Bookings', 'erosity'); ?></h3>
                <div class="stat-value"><?php echo count($bookings); ?></div>
            </div>
            
            <div class="erosity-stat-card">
                <h3><?php _e('Unread Messages', 'erosity'); ?></h3>
                <div class="stat-value">0</div>
            </div>
        </div>
        
        <?php if (!Erosity_User::has_complete_profile($user_id)): ?>
        <div class="erosity-notice erosity-notice-warning">
            <p><?php _e('Please complete your profile to list properties.', 'erosity'); ?></p>
            <a href="#profile" class="erosity-button"><?php _e('Complete Profile', 'erosity'); ?></a>
        </div>
        <?php endif; ?>
        <?php
    }
    
    /**
     * Render properties tab
     *
     * @param int $user_id User ID
     */
    private static function render_properties_tab($user_id) {
        $properties = get_posts(array(
            'post_type' => array('erosity_property', 'erosity_toy'),
            'author' => $user_id,
            'posts_per_page' => -1,
        ));
        
        ?>
        <h2><?php _e('My Properties', 'erosity'); ?></h2>
        
        <p>
            <a href="<?php echo home_url('/add-property'); ?>" class="erosity-button">
                <?php _e('Add New Property', 'erosity'); ?>
            </a>
            <a href="<?php echo home_url('/add-toy'); ?>" class="erosity-button">
                <?php _e('Add New Toy', 'erosity'); ?>
            </a>
        </p>
        
        <?php if (empty($properties)): ?>
            <p><?php _e('You have not added any properties yet.', 'erosity'); ?></p>
        <?php else: ?>
            <table class="erosity-table">
                <thead>
                    <tr>
                        <th><?php _e('Title', 'erosity'); ?></th>
                        <th><?php _e('Type', 'erosity'); ?></th>
                        <th><?php _e('Status', 'erosity'); ?></th>
                        <th><?php _e('Actions', 'erosity'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($properties as $property): ?>
                    <tr>
                        <td><?php echo esc_html($property->post_title); ?></td>
                        <td><?php echo esc_html($property->post_type === 'erosity_property' ? __('Property', 'erosity') : __('Toy', 'erosity')); ?></td>
                        <td><?php echo esc_html($property->post_status); ?></td>
                        <td>
                            <a href="<?php echo get_permalink($property->ID); ?>"><?php _e('View', 'erosity'); ?></a> |
                            <a href="<?php echo home_url('/edit-property/' . $property->ID); ?>"><?php _e('Edit', 'erosity'); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php
    }
    
    /**
     * Render bookings tab
     *
     * @param int $user_id User ID
     */
    private static function render_bookings_tab($user_id) {
        ?>
        <h2><?php _e('My Bookings', 'erosity'); ?></h2>
        <p><?php _e('Your booking history will be displayed here.', 'erosity'); ?></p>
        <?php
    }
    
    /**
     * Render messages tab
     *
     * @param int $user_id User ID
     */
    private static function render_messages_tab($user_id) {
        ?>
        <h2><?php _e('Messages', 'erosity'); ?></h2>
        <p><?php _e('Your messages will be displayed here.', 'erosity'); ?></p>
        <?php
    }
    
    /**
     * Render profile tab
     *
     * @param int $user_id User ID
     */
    private static function render_profile_tab($user_id) {
        $user = get_userdata($user_id);
        $user_data = Erosity_User::get_user_data($user_id);
        
        ?>
        <h2><?php _e('Profile Settings', 'erosity'); ?></h2>
        
        <form id="erosity-profile-form" method="post">
            <h3><?php _e('Personal Information', 'erosity'); ?></h3>
            
            <p>
                <label for="first_name"><?php _e('First Name', 'erosity'); ?></label>
                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($user->first_name); ?>">
            </p>
            
            <p>
                <label for="last_name"><?php _e('Last Name', 'erosity'); ?></label>
                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($user->last_name); ?>">
            </p>
            
            <h3><?php _e('Address', 'erosity'); ?></h3>
            
            <p>
                <label for="street"><?php _e('Street', 'erosity'); ?></label>
                <input type="text" name="street" id="street" value="<?php echo esc_attr($user_data->street ?? ''); ?>">
            </p>
            
            <p>
                <label for="house_number"><?php _e('House Number', 'erosity'); ?></label>
                <input type="text" name="house_number" id="house_number" value="<?php echo esc_attr($user_data->house_number ?? ''); ?>">
            </p>
            
            <p>
                <label for="postal_code"><?php _e('Postal Code', 'erosity'); ?></label>
                <input type="text" name="postal_code" id="postal_code" value="<?php echo esc_attr($user_data->postal_code ?? ''); ?>">
            </p>
            
            <p>
                <label for="city"><?php _e('City', 'erosity'); ?></label>
                <input type="text" name="city" id="city" value="<?php echo esc_attr($user_data->city ?? ''); ?>">
            </p>
            
            <p>
                <label for="country"><?php _e('Country', 'erosity'); ?></label>
                <input type="text" name="country" id="country" value="<?php echo esc_attr($user_data->country ?? ''); ?>">
            </p>
            
            <h3><?php _e('Payment Information', 'erosity'); ?></h3>
            
            <p>
                <label for="iban"><?php _e('IBAN', 'erosity'); ?></label>
                <input type="text" name="iban" id="iban" value="<?php echo esc_attr($user_data->iban ?? ''); ?>">
            </p>
            
            <p>
                <label for="bic"><?php _e('BIC', 'erosity'); ?></label>
                <input type="text" name="bic" id="bic" value="<?php echo esc_attr($user_data->bic ?? ''); ?>">
            </p>
            
            <p>
                <label for="vat_number"><?php _e('VAT Number (Optional)', 'erosity'); ?></label>
                <input type="text" name="vat_number" id="vat_number" value="<?php echo esc_attr($user_data->vat_number ?? ''); ?>">
            </p>
            
            <p>
                <button type="submit" class="erosity-button"><?php _e('Save Profile', 'erosity'); ?></button>
            </p>
        </form>
        <?php
    }
}
