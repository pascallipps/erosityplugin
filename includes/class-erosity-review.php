<?php
/**
 * Review Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Review Class
 */
class Erosity_Review {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
    }
    
    /**
     * Add meta boxes
     */
    public static function add_meta_boxes() {
        add_meta_box(
            'erosity_review_details',
            __('Review Details', 'erosity'),
            array(__CLASS__, 'render_details_meta_box'),
            'erosity_review',
            'side',
            'default'
        );
    }
    
    /**
     * Render details meta box
     *
     * @param WP_Post $post Post object
     */
    public static function render_details_meta_box($post) {
        $rating = get_post_meta($post->ID, '_rating', true) ?: 0;
        $property_id = get_post_meta($post->ID, '_property_id', true);
        
        echo '<p>';
        echo '<label>' . __('Rating:', 'erosity') . '</label><br>';
        echo '<select name="rating">';
        for ($i = 1; $i <= 5; $i++) {
            echo '<option value="' . $i . '"' . selected($rating, $i, false) . '>' . $i . ' ' . __('Stars', 'erosity') . '</option>';
        }
        echo '</select>';
        echo '</p>';
        
        echo '<p>';
        echo '<label>' . __('Property ID:', 'erosity') . '</label><br>';
        echo '<input type="number" name="property_id" value="' . esc_attr($property_id) . '" style="width:100%">';
        echo '</p>';
    }
    
    /**
     * Create review
     *
     * @param array $data Review data
     * @return int|WP_Error
     */
    public static function create_review($data) {
        $review_data = array(
            'post_type' => 'erosity_review',
            'post_status' => 'pending',
            'post_title' => sprintf(__('Review by %s', 'erosity'), $data['reviewer_name']),
            'post_content' => $data['message'],
            'post_author' => $data['reviewer_id'],
        );
        
        $review_id = wp_insert_post($review_data);
        
        if (is_wp_error($review_id)) {
            return $review_id;
        }
        
        update_post_meta($review_id, '_rating', intval($data['rating']));
        update_post_meta($review_id, '_property_id', intval($data['property_id']));
        update_post_meta($review_id, '_booking_id', intval($data['booking_id']));
        
        return $review_id;
    }
    
    /**
     * Get property reviews
     *
     * @param int $property_id Property ID
     * @return array
     */
    public static function get_property_reviews($property_id) {
        $args = array(
            'post_type' => 'erosity_review',
            'post_status' => 'publish',
            'meta_key' => '_property_id',
            'meta_value' => $property_id,
            'posts_per_page' => -1,
        );
        
        return get_posts($args);
    }
    
    /**
     * Get average rating
     *
     * @param int $property_id Property ID
     * @return float
     */
    public static function get_average_rating($property_id) {
        $reviews = self::get_property_reviews($property_id);
        
        if (empty($reviews)) {
            return 0;
        }
        
        $total = 0;
        foreach ($reviews as $review) {
            $total += intval(get_post_meta($review->ID, '_rating', true));
        }
        
        return round($total / count($reviews), 1);
    }
}
