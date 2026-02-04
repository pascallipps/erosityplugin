<?php
/**
 * Taxonomies
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Taxonomies Class
 */
class Erosity_Taxonomies {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_taxonomies'));
    }
    
    /**
     * Register all taxonomies
     */
    public static function register_taxonomies() {
        self::register_property_category();
        self::register_toy_category();
        self::register_amenity();
        self::register_property_tag();
    }
    
    /**
     * Register Property Category Taxonomy
     */
    private static function register_property_category() {
        $labels = array(
            'name'              => __('Property Categories', 'erosity'),
            'singular_name'     => __('Property Category', 'erosity'),
            'search_items'      => __('Search Categories', 'erosity'),
            'all_items'         => __('All Categories', 'erosity'),
            'parent_item'       => __('Parent Category', 'erosity'),
            'parent_item_colon' => __('Parent Category:', 'erosity'),
            'edit_item'         => __('Edit Category', 'erosity'),
            'update_item'       => __('Update Category', 'erosity'),
            'add_new_item'      => __('Add New Category', 'erosity'),
            'new_item_name'     => __('New Category Name', 'erosity'),
            'menu_name'         => __('Categories', 'erosity'),
        );
        
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'property-category'),
            'show_in_rest'      => true,
        );
        
        register_taxonomy('erosity_property_cat', array('erosity_property'), $args);
    }
    
    /**
     * Register Toy Category Taxonomy
     */
    private static function register_toy_category() {
        $labels = array(
            'name'              => __('Toy Categories', 'erosity'),
            'singular_name'     => __('Toy Category', 'erosity'),
            'search_items'      => __('Search Categories', 'erosity'),
            'all_items'         => __('All Categories', 'erosity'),
            'parent_item'       => __('Parent Category', 'erosity'),
            'parent_item_colon' => __('Parent Category:', 'erosity'),
            'edit_item'         => __('Edit Category', 'erosity'),
            'update_item'       => __('Update Category', 'erosity'),
            'add_new_item'      => __('Add New Category', 'erosity'),
            'new_item_name'     => __('New Category Name', 'erosity'),
            'menu_name'         => __('Categories', 'erosity'),
        );
        
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'toy-category'),
            'show_in_rest'      => true,
        );
        
        register_taxonomy('erosity_toy_cat', array('erosity_toy'), $args);
    }
    
    /**
     * Register Amenity Taxonomy
     */
    private static function register_amenity() {
        $labels = array(
            'name'              => __('Amenities', 'erosity'),
            'singular_name'     => __('Amenity', 'erosity'),
            'search_items'      => __('Search Amenities', 'erosity'),
            'all_items'         => __('All Amenities', 'erosity'),
            'parent_item'       => null,
            'parent_item_colon' => null,
            'edit_item'         => __('Edit Amenity', 'erosity'),
            'update_item'       => __('Update Amenity', 'erosity'),
            'add_new_item'      => __('Add New Amenity', 'erosity'),
            'new_item_name'     => __('New Amenity Name', 'erosity'),
            'menu_name'         => __('Amenities', 'erosity'),
        );
        
        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'amenity'),
            'show_in_rest'      => true,
        );
        
        register_taxonomy('erosity_amenity', array('erosity_property', 'erosity_toy'), $args);
    }
    
    /**
     * Register Property Tag Taxonomy
     */
    private static function register_property_tag() {
        $labels = array(
            'name'              => __('Tags', 'erosity'),
            'singular_name'     => __('Tag', 'erosity'),
            'search_items'      => __('Search Tags', 'erosity'),
            'all_items'         => __('All Tags', 'erosity'),
            'parent_item'       => null,
            'parent_item_colon' => null,
            'edit_item'         => __('Edit Tag', 'erosity'),
            'update_item'       => __('Update Tag', 'erosity'),
            'add_new_item'      => __('Add New Tag', 'erosity'),
            'new_item_name'     => __('New Tag Name', 'erosity'),
            'menu_name'         => __('Tags', 'erosity'),
        );
        
        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'property-tag'),
            'show_in_rest'      => true,
        );
        
        register_taxonomy('erosity_property_tag', array('erosity_property', 'erosity_toy'), $args);
    }
}
