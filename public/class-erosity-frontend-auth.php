<?php
/**
 * Frontend Authentication
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Frontend Auth Class
 */
class Erosity_Frontend_Auth {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('wp_ajax_nopriv_erosity_login', array(__CLASS__, 'handle_login'));
        add_action('wp_ajax_nopriv_erosity_register', array(__CLASS__, 'handle_register'));
        add_action('wp_ajax_erosity_logout', array(__CLASS__, 'handle_logout'));
    }
    
    /**
     * Render login form
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_login_form($atts) {
        if (is_user_logged_in()) {
            return '<p>' . __('You are already logged in.', 'erosity') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="erosity-login-form">
            <h2><?php _e('Login', 'erosity'); ?></h2>
            
            <form id="erosity-login-form" method="post">
                <p>
                    <label for="username"><?php _e('Username or Email', 'erosity'); ?></label>
                    <input type="text" name="username" id="username" required>
                </p>
                
                <p>
                    <label for="password"><?php _e('Password', 'erosity'); ?></label>
                    <input type="password" name="password" id="password" required>
                </p>
                
                <p>
                    <label>
                        <input type="checkbox" name="remember" value="1">
                        <?php _e('Remember Me', 'erosity'); ?>
                    </label>
                </p>
                
                <p>
                    <button type="submit" class="erosity-button"><?php _e('Login', 'erosity'); ?></button>
                </p>
                
                <div class="erosity-message"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render registration form
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_register_form($atts) {
        if (is_user_logged_in()) {
            return '<p>' . __('You are already logged in.', 'erosity') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="erosity-register-form">
            <h2><?php _e('Register', 'erosity'); ?></h2>
            
            <form id="erosity-register-form" method="post">
                <p>
                    <label for="reg_username"><?php _e('Username', 'erosity'); ?></label>
                    <input type="text" name="username" id="reg_username" required>
                </p>
                
                <p>
                    <label for="reg_email"><?php _e('Email', 'erosity'); ?></label>
                    <input type="email" name="email" id="reg_email" required>
                </p>
                
                <p>
                    <label for="reg_password"><?php _e('Password', 'erosity'); ?></label>
                    <input type="password" name="password" id="reg_password" required>
                </p>
                
                <p>
                    <label for="reg_password_confirm"><?php _e('Confirm Password', 'erosity'); ?></label>
                    <input type="password" name="password_confirm" id="reg_password_confirm" required>
                </p>
                
                <p>
                    <label>
                        <input type="checkbox" name="age_confirm" value="1" required>
                        <?php _e('I confirm that I am at least 18 years old', 'erosity'); ?>
                    </label>
                </p>
                
                <p>
                    <label>
                        <input type="checkbox" name="terms" value="1" required>
                        <?php _e('I agree to the Terms and Conditions', 'erosity'); ?>
                    </label>
                </p>
                
                <p>
                    <button type="submit" class="erosity-button"><?php _e('Register', 'erosity'); ?></button>
                </p>
                
                <div class="erosity-message"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Handle login request
     */
    public static function handle_login() {
        check_ajax_referer('erosity_nonce', 'nonce');
        
        $username = sanitize_text_field($_POST['username']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;
        
        $user = wp_signon(array(
            'user_login' => $username,
            'user_password' => $password,
            'remember' => $remember,
        ), is_ssl());
        
        if (is_wp_error($user)) {
            wp_send_json_error(array(
                'message' => $user->get_error_message(),
            ));
        }
        
        wp_send_json_success(array(
            'message' => __('Login successful!', 'erosity'),
            'redirect' => home_url('/dashboard'),
        ));
    }
    
    /**
     * Handle registration request
     */
    public static function handle_register() {
        check_ajax_referer('erosity_nonce', 'nonce');
        
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        
        // Validate passwords match
        if ($password !== $password_confirm) {
            wp_send_json_error(array(
                'message' => __('Passwords do not match.', 'erosity'),
            ));
        }
        
        // Create user
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(array(
                'message' => $user_id->get_error_message(),
            ));
        }
        
        // Log user in
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        wp_send_json_success(array(
            'message' => __('Registration successful!', 'erosity'),
            'redirect' => home_url('/dashboard'),
        ));
    }
    
    /**
     * Handle logout request
     */
    public static function handle_logout() {
        check_ajax_referer('erosity_nonce', 'nonce');
        
        wp_logout();
        
        wp_send_json_success(array(
            'message' => __('Logged out successfully.', 'erosity'),
            'redirect' => home_url(),
        ));
    }
}
