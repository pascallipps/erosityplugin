<?php
/**
 * Admin Support
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Admin Support Class
 */
class Erosity_Admin_Support {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post_erosity_ticket', array(__CLASS__, 'save_ticket_meta'));
    }
    
    /**
     * Add meta boxes
     */
    public static function add_meta_boxes() {
        add_meta_box(
            'erosity_ticket_details',
            __('Ticket Details', 'erosity'),
            array(__CLASS__, 'render_ticket_details'),
            'erosity_ticket',
            'side',
            'default'
        );
        
        add_meta_box(
            'erosity_ticket_response',
            __('Respond to Ticket', 'erosity'),
            array(__CLASS__, 'render_ticket_response'),
            'erosity_ticket',
            'normal',
            'default'
        );
    }
    
    /**
     * Render ticket details meta box
     *
     * @param WP_Post $post Post object
     */
    public static function render_ticket_details($post) {
        $status = get_post_meta($post->ID, '_ticket_status', true) ?: 'open';
        $priority = get_post_meta($post->ID, '_ticket_priority', true) ?: 'normal';
        $contact_email = get_post_meta($post->ID, '_contact_email', true);
        $contact_name = get_post_meta($post->ID, '_contact_name', true);
        
        ?>
        <p>
            <strong><?php _e('Status:', 'erosity'); ?></strong><br>
            <select name="ticket_status" style="width: 100%;">
                <option value="open" <?php selected($status, 'open'); ?>><?php _e('Open', 'erosity'); ?></option>
                <option value="in_progress" <?php selected($status, 'in_progress'); ?>><?php _e('In Progress', 'erosity'); ?></option>
                <option value="resolved" <?php selected($status, 'resolved'); ?>><?php _e('Resolved', 'erosity'); ?></option>
                <option value="closed" <?php selected($status, 'closed'); ?>><?php _e('Closed', 'erosity'); ?></option>
            </select>
        </p>
        
        <p>
            <strong><?php _e('Priority:', 'erosity'); ?></strong><br>
            <select name="ticket_priority" style="width: 100%;">
                <option value="low" <?php selected($priority, 'low'); ?>><?php _e('Low', 'erosity'); ?></option>
                <option value="normal" <?php selected($priority, 'normal'); ?>><?php _e('Normal', 'erosity'); ?></option>
                <option value="high" <?php selected($priority, 'high'); ?>><?php _e('High', 'erosity'); ?></option>
                <option value="urgent" <?php selected($priority, 'urgent'); ?>><?php _e('Urgent', 'erosity'); ?></option>
            </select>
        </p>
        
        <p>
            <strong><?php _e('Contact Name:', 'erosity'); ?></strong><br>
            <?php echo esc_html($contact_name); ?>
        </p>
        
        <p>
            <strong><?php _e('Contact Email:', 'erosity'); ?></strong><br>
            <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
        </p>
        <?php
    }
    
    /**
     * Render ticket response meta box
     *
     * @param WP_Post $post Post object
     */
    public static function render_ticket_response($post) {
        ?>
        <p>
            <label for="ticket_response"><strong><?php _e('Response:', 'erosity'); ?></strong></label><br>
            <textarea name="ticket_response" 
                      id="ticket_response" 
                      rows="10" 
                      style="width: 100%;"></textarea>
        </p>
        
        <p>
            <label>
                <input type="checkbox" name="send_email_notification" value="1">
                <?php _e('Send email notification to customer', 'erosity'); ?>
            </label>
        </p>
        <?php
    }
    
    /**
     * Save ticket meta
     *
     * @param int $post_id Post ID
     */
    public static function save_ticket_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save status
        if (isset($_POST['ticket_status'])) {
            update_post_meta($post_id, '_ticket_status', sanitize_text_field($_POST['ticket_status']));
        }
        
        // Save priority
        if (isset($_POST['ticket_priority'])) {
            update_post_meta($post_id, '_ticket_priority', sanitize_text_field($_POST['ticket_priority']));
        }
        
        // Save response
        if (isset($_POST['ticket_response']) && !empty($_POST['ticket_response'])) {
            $response = sanitize_textarea_field($_POST['ticket_response']);
            
            // Add response as comment
            wp_insert_comment(array(
                'comment_post_ID' => $post_id,
                'comment_content' => $response,
                'comment_author' => wp_get_current_user()->display_name,
                'comment_author_email' => wp_get_current_user()->user_email,
                'comment_type' => 'ticket_response',
                'user_id' => get_current_user_id(),
            ));
            
            // Send email notification if requested
            if (isset($_POST['send_email_notification'])) {
                $contact_email = get_post_meta($post_id, '_contact_email', true);
                if ($contact_email) {
                    wp_mail(
                        $contact_email,
                        sprintf(__('Response to your support ticket #%d', 'erosity'), $post_id),
                        $response
                    );
                }
            }
        }
    }
}
