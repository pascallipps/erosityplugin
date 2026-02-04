<?php
/**
 * Custom Post Types
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Post Types Class
 */
class Erosity_Post_Types {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_types'));
    }
    
    /**
     * Register all custom post types
     */
    public static function register_post_types() {
        self::register_property_post_type();
        self::register_toy_post_type();
        self::register_booking_post_type();
        self::register_review_post_type();
        self::register_message_post_type();
        self::register_ticket_post_type();
        self::register_extra_post_type();
    }
    
    /**
     * Register Property Post Type
     */
    private static function register_property_post_type() {
        $labels = array(
            'name'                  => __('Properties', 'erosity'),
            'singular_name'         => __('Property', 'erosity'),
            'menu_name'             => __('Properties', 'erosity'),
            'add_new'               => __('Add New', 'erosity'),
            'add_new_item'          => __('Add New Property', 'erosity'),
            'edit_item'             => __('Edit Property', 'erosity'),
            'new_item'              => __('New Property', 'erosity'),
            'view_item'             => __('View Property', 'erosity'),
            'search_items'          => __('Search Properties', 'erosity'),
            'not_found'             => __('No properties found', 'erosity'),
            'not_found_in_trash'    => __('No properties found in trash', 'erosity'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => 'erosity',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'property'),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 20,
            'supports'              => array('title', 'editor', 'thumbnail', 'author'),
            'show_in_rest'          => true,
        );
        
        register_post_type('erosity_property', $args);
    }
    
    /**
     * Register Toy Post Type
     */
    private static function register_toy_post_type() {
        $labels = array(
            'name'                  => __('Toys', 'erosity'),
            'singular_name'         => __('Toy', 'erosity'),
            'menu_name'             => __('Toys', 'erosity'),
            'add_new'               => __('Add New', 'erosity'),
            'add_new_item'          => __('Add New Toy', 'erosity'),
            'edit_item'             => __('Edit Toy', 'erosity'),
            'new_item'              => __('New Toy', 'erosity'),
            'view_item'             => __('View Toy', 'erosity'),
            'search_items'          => __('Search Toys', 'erosity'),
            'not_found'             => __('No toys found', 'erosity'),
            'not_found_in_trash'    => __('No toys found in trash', 'erosity'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => 'erosity',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'toy'),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor', 'thumbnail', 'author'),
            'show_in_rest'          => true,
        );
        
        register_post_type('erosity_toy', $args);
    }
    
    /**
     * Register Booking Post Type
     */
    private static function register_booking_post_type() {
        $labels = array(
            'name'                  => __('Bookings', 'erosity'),
            'singular_name'         => __('Booking', 'erosity'),
            'menu_name'             => __('Bookings', 'erosity'),
            'add_new'               => __('Add New', 'erosity'),
            'add_new_item'          => __('Add New Booking', 'erosity'),
            'edit_item'             => __('Edit Booking', 'erosity'),
            'new_item'              => __('New Booking', 'erosity'),
            'view_item'             => __('View Booking', 'erosity'),
            'search_items'          => __('Search Bookings', 'erosity'),
            'not_found'             => __('No bookings found', 'erosity'),
            'not_found_in_trash'    => __('No bookings found in trash', 'erosity'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => 'erosity',
            'query_var'             => true,
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'supports'              => array('title'),
            'show_in_rest'          => true,
        );
        
        register_post_type('erosity_booking', $args);
    }
    
    /**
     * Register Review Post Type
     */
    private static function register_review_post_type() {
        $labels = array(
            'name'                  => __('Reviews', 'erosity'),
            'singular_name'         => __('Review', 'erosity'),
            'menu_name'             => __('Reviews', 'erosity'),
            'add_new'               => __('Add New', 'erosity'),
            'add_new_item'          => __('Add New Review', 'erosity'),
            'edit_item'             => __('Edit Review', 'erosity'),
            'new_item'              => __('New Review', 'erosity'),
            'view_item'             => __('View Review', 'erosity'),
            'search_items'          => __('Search Reviews', 'erosity'),
            'not_found'             => __('No reviews found', 'erosity'),
            'not_found_in_trash'    => __('No reviews found in trash', 'erosity'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => 'erosity',
            'query_var'             => true,
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor', 'author'),
            'show_in_rest'          => true,
        );
        
        register_post_type('erosity_review', $args);
    }
    
    /**
     * Register Message Post Type
     */
    private static function register_message_post_type() {
        $labels = array(
            'name'                  => __('Messages', 'erosity'),
            'singular_name'         => __('Message', 'erosity'),
            'menu_name'             => __('Messages', 'erosity'),
            'add_new'               => __('Add New', 'erosity'),
            'add_new_item'          => __('Add New Message', 'erosity'),
            'edit_item'             => __('Edit Message', 'erosity'),
            'new_item'              => __('New Message', 'erosity'),
            'view_item'             => __('View Message', 'erosity'),
            'search_items'          => __('Search Messages', 'erosity'),
            'not_found'             => __('No messages found', 'erosity'),
            'not_found_in_trash'    => __('No messages found in trash', 'erosity'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => 'erosity',
            'query_var'             => true,
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor', 'author'),
            'show_in_rest'          => false,
        );
        
        register_post_type('erosity_message', $args);
    }
    
    /**
     * Register Support Ticket Post Type
     */
    private static function register_ticket_post_type() {
        $labels = array(
            'name'                  => __('Support Tickets', 'erosity'),
            'singular_name'         => __('Support Ticket', 'erosity'),
            'menu_name'             => __('Support', 'erosity'),
            'add_new'               => __('Add New', 'erosity'),
            'add_new_item'          => __('Add New Ticket', 'erosity'),
            'edit_item'             => __('Edit Ticket', 'erosity'),
            'new_item'              => __('New Ticket', 'erosity'),
            'view_item'             => __('View Ticket', 'erosity'),
            'search_items'          => __('Search Tickets', 'erosity'),
            'not_found'             => __('No tickets found', 'erosity'),
            'not_found_in_trash'    => __('No tickets found in trash', 'erosity'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => 'erosity',
            'query_var'             => true,
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor', 'author', 'comments'),
            'show_in_rest'          => false,
        );
        
        register_post_type('erosity_ticket', $args);
    }
    
    /**
     * Register Extra Post Type
     */
    private static function register_extra_post_type() {
        $labels = array(
            'name'                  => __('Extras', 'erosity'),
            'singular_name'         => __('Extra', 'erosity'),
            'menu_name'             => __('Extras', 'erosity'),
            'add_new'               => __('Add New', 'erosity'),
            'add_new_item'          => __('Add New Extra', 'erosity'),
            'edit_item'             => __('Edit Extra', 'erosity'),
            'new_item'              => __('New Extra', 'erosity'),
            'view_item'             => __('View Extra', 'erosity'),
            'search_items'          => __('Search Extras', 'erosity'),
            'not_found'             => __('No extras found', 'erosity'),
            'not_found_in_trash'    => __('No extras found in trash', 'erosity'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => false, // Will be shown as submenu
            'query_var'             => true,
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'show_in_rest'          => true,
        );
        
        register_post_type('erosity_extra', $args);
    }
}
