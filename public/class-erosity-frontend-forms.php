<?php
/**
 * Frontend Forms (Multi-step property/toy submission)
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Frontend Forms Class
 */
class Erosity_Frontend_Forms {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('wp_ajax_erosity_save_property_step', array(__CLASS__, 'save_property_step'));
        add_action('wp_ajax_erosity_save_toy_step', array(__CLASS__, 'save_toy_step'));
    }
    
    /**
     * Render property form
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_property_form($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to add a property.', 'erosity') . '</p>';
        }
        
        $user_id = get_current_user_id();
        if (!Erosity_User::has_complete_profile($user_id)) {
            return '<p>' . __('Please complete your profile before adding a property.', 'erosity') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="erosity-property-form">
            <h1><?php _e('Add Property', 'erosity'); ?></h1>
            
            <div class="erosity-form-steps">
                <div class="step active" data-step="1">
                    <span class="step-number">1</span>
                    <span class="step-label"><?php _e('Basic Info', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="2">
                    <span class="step-number">2</span>
                    <span class="step-label"><?php _e('Properties', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="3">
                    <span class="step-number">3</span>
                    <span class="step-label"><?php _e('Address', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="4">
                    <span class="step-number">4</span>
                    <span class="step-label"><?php _e('Images', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="5">
                    <span class="step-number">5</span>
                    <span class="step-label"><?php _e('Amenities', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="6">
                    <span class="step-number">6</span>
                    <span class="step-label"><?php _e('Rules', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="7">
                    <span class="step-number">7</span>
                    <span class="step-label"><?php _e('Pricing', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="8">
                    <span class="step-number">8</span>
                    <span class="step-label"><?php _e('Extras', 'erosity'); ?></span>
                </div>
                <div class="step" data-step="9">
                    <span class="step-number">9</span>
                    <span class="step-label"><?php _e('Cancellation', 'erosity'); ?></span>
                </div>
            </div>
            
            <form id="erosity-property-form-steps">
                <!-- Step 1: Basic Info -->
                <div class="form-step active" data-step="1">
                    <h2><?php _e('Step 1: Basic Information', 'erosity'); ?></h2>
                    
                    <p>
                        <label for="property_name"><?php _e('Property Name', 'erosity'); ?> *</label>
                        <input type="text" name="property_name" id="property_name" required>
                    </p>
                    
                    <p>
                        <label for="property_category"><?php _e('Category', 'erosity'); ?> *</label>
                        <select name="property_category" id="property_category" required>
                            <option value=""><?php _e('Select Category', 'erosity'); ?></option>
                            <!-- Categories will be populated dynamically -->
                        </select>
                    </p>
                    
                    <p>
                        <label for="short_description"><?php _e('Short Description', 'erosity'); ?></label>
                        <textarea name="short_description" id="short_description" rows="3"></textarea>
                    </p>
                    
                    <p>
                        <label for="description"><?php _e('Description', 'erosity'); ?></label>
                        <textarea name="description" id="description" rows="6"></textarea>
                    </p>
                    
                    <p class="form-navigation">
                        <button type="button" class="erosity-button next-step"><?php _e('Next', 'erosity'); ?></button>
                    </p>
                </div>
                
                <!-- Additional steps would be added here -->
                <div class="form-step" data-step="2">
                    <h2><?php _e('Step 2: Property Details', 'erosity'); ?></h2>
                    <p><?php _e('Property details form will be implemented here', 'erosity'); ?></p>
                    
                    <p class="form-navigation">
                        <button type="button" class="erosity-button prev-step"><?php _e('Previous', 'erosity'); ?></button>
                        <button type="button" class="erosity-button next-step"><?php _e('Next', 'erosity'); ?></button>
                    </p>
                </div>
                
                <!-- More steps... -->
                
                <div class="erosity-form-message"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render toy form
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_toy_form($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to add a toy.', 'erosity') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="erosity-toy-form">
            <h1><?php _e('Add Toy', 'erosity'); ?></h1>
            <p><?php _e('Toy listing form will be implemented here', 'erosity'); ?></p>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Save property step via AJAX
     */
    public static function save_property_step() {
        check_ajax_referer('erosity_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Please log in.', 'erosity')));
        }
        
        $step = intval($_POST['step']);
        $data = $_POST['data'];
        
        // Save as draft
        $property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
        
        if ($property_id) {
            // Update existing property
            wp_update_post(array(
                'ID' => $property_id,
                'post_status' => 'draft',
            ));
        } else {
            // Create new property
            $property_id = wp_insert_post(array(
                'post_type' => 'erosity_property',
                'post_status' => 'draft',
                'post_title' => $data['property_name'] ?? 'Draft Property',
                'post_author' => get_current_user_id(),
            ));
        }
        
        if (is_wp_error($property_id)) {
            wp_send_json_error(array('message' => $property_id->get_error_message()));
        }
        
        // Save step data as meta
        update_post_meta($property_id, '_step_' . $step . '_data', $data);
        update_post_meta($property_id, '_current_step', $step);
        
        wp_send_json_success(array(
            'message' => __('Step saved successfully.', 'erosity'),
            'property_id' => $property_id,
        ));
    }
    
    /**
     * Save toy step via AJAX
     */
    public static function save_toy_step() {
        check_ajax_referer('erosity_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Please log in.', 'erosity')));
        }
        
        // Similar implementation to save_property_step
        wp_send_json_success(array('message' => __('Toy data saved.', 'erosity')));
    }
}
