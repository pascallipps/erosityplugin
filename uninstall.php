<?php
/**
 * Uninstall Script
 *
 * @package Erosity
 */

// Exit if accessed directly or not uninstalling
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Delete all custom post types
$post_types = array(
    'erosity_property',
    'erosity_toy',
    'erosity_booking',
    'erosity_review',
    'erosity_message',
    'erosity_ticket',
    'erosity_extra',
);

foreach ($post_types as $post_type) {
    $posts = get_posts(array(
        'post_type' => $post_type,
        'numberposts' => -1,
        'post_status' => 'any',
    ));
    
    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
    }
}

// Delete all taxonomies
$taxonomies = array(
    'erosity_property_cat',
    'erosity_toy_cat',
    'erosity_amenity',
    'erosity_property_tag',
);

foreach ($taxonomies as $taxonomy) {
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ));
    
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            wp_delete_term($term->term_id, $taxonomy);
        }
    }
}

// Drop custom tables
$tables = array(
    $wpdb->prefix . 'erosity_user_data',
    $wpdb->prefix . 'erosity_availability',
    $wpdb->prefix . 'erosity_pricing',
    $wpdb->prefix . 'erosity_extras',
    $wpdb->prefix . 'erosity_rooms',
    $wpdb->prefix . 'erosity_coupons',
    $wpdb->prefix . 'erosity_booking_extras',
    $wpdb->prefix . 'erosity_cancellation_policies',
    $wpdb->prefix . 'erosity_commissions',
    $wpdb->prefix . 'erosity_announcements',
    $wpdb->prefix . 'erosity_email_templates',
);

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS $table");
}

// Delete options
delete_option('erosity_settings');
delete_option('erosity_db_version');

// Delete transients
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_erosity_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_erosity_%'");

// Clear any cached data
wp_cache_flush();
