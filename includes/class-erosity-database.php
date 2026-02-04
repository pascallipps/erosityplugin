<?php
/**
 * Database
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Database Class
 */
class Erosity_Database {
    
    /**
     * Create all database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // User extended data table
        $table_name = $wpdb->prefix . 'erosity_user_data';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            street varchar(255) DEFAULT NULL,
            house_number varchar(50) DEFAULT NULL,
            postal_code varchar(20) DEFAULT NULL,
            city varchar(255) DEFAULT NULL,
            country varchar(100) DEFAULT NULL,
            vat_number varchar(100) DEFAULT NULL,
            iban varchar(100) DEFAULT NULL,
            bic varchar(50) DEFAULT NULL,
            phone varchar(50) DEFAULT NULL,
            age_verified tinyint(1) DEFAULT 0,
            age_verified_date datetime DEFAULT NULL,
            stripe_account_id varchar(255) DEFAULT NULL,
            stripe_account_status varchar(50) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY user_id (user_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Availability table
        $table_name = $wpdb->prefix . 'erosity_availability';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            property_id bigint(20) NOT NULL,
            property_type varchar(50) NOT NULL,
            blocked_date date NOT NULL,
            blocked_start_time time DEFAULT NULL,
            blocked_end_time time DEFAULT NULL,
            reason varchar(255) DEFAULT NULL,
            booking_id bigint(20) DEFAULT NULL,
            is_manual tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY property_id (property_id),
            KEY blocked_date (blocked_date),
            KEY booking_id (booking_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Pricing table
        $table_name = $wpdb->prefix . 'erosity_pricing';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            property_id bigint(20) NOT NULL,
            property_type varchar(50) NOT NULL,
            pricing_type varchar(50) NOT NULL,
            day_of_week tinyint(1) DEFAULT NULL,
            time_start time DEFAULT NULL,
            time_end time DEFAULT NULL,
            base_price decimal(10,2) NOT NULL,
            base_persons int(11) DEFAULT 2,
            extra_person_price decimal(10,2) DEFAULT 0.00,
            holiday_price decimal(10,2) DEFAULT NULL,
            min_duration int(11) DEFAULT 1,
            surcharge_percent decimal(5,2) DEFAULT 0.00,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY property_id (property_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Extras table
        $table_name = $wpdb->prefix . 'erosity_extras';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            property_id bigint(20) NOT NULL,
            property_type varchar(50) NOT NULL,
            title varchar(255) NOT NULL,
            description text DEFAULT NULL,
            image_id bigint(20) DEFAULT NULL,
            price decimal(10,2) NOT NULL,
            price_type varchar(50) NOT NULL,
            max_quantity int(11) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY property_id (property_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Rooms table
        $table_name = $wpdb->prefix . 'erosity_rooms';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            property_id bigint(20) NOT NULL,
            title varchar(255) NOT NULL,
            description text DEFAULT NULL,
            gallery_ids text DEFAULT NULL,
            room_order int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY property_id (property_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Coupons table
        $table_name = $wpdb->prefix . 'erosity_coupons';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            code varchar(100) NOT NULL,
            type varchar(50) NOT NULL,
            discount_type varchar(50) NOT NULL,
            discount_value decimal(10,2) NOT NULL,
            property_id bigint(20) DEFAULT NULL,
            property_type varchar(50) DEFAULT NULL,
            vendor_id bigint(20) DEFAULT NULL,
            usage_limit int(11) DEFAULT NULL,
            usage_count int(11) DEFAULT 0,
            valid_from datetime DEFAULT NULL,
            valid_until datetime DEFAULT NULL,
            is_active tinyint(1) DEFAULT 1,
            created_by bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY code (code),
            KEY vendor_id (vendor_id),
            KEY property_id (property_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Booking extras table
        $table_name = $wpdb->prefix . 'erosity_booking_extras';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            booking_id bigint(20) NOT NULL,
            extra_id bigint(20) NOT NULL,
            quantity int(11) DEFAULT 1,
            price decimal(10,2) NOT NULL,
            total_price decimal(10,2) NOT NULL,
            PRIMARY KEY  (id),
            KEY booking_id (booking_id),
            KEY extra_id (extra_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Cancellation policies table
        $table_name = $wpdb->prefix . 'erosity_cancellation_policies';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            property_id bigint(20) NOT NULL,
            property_type varchar(50) NOT NULL,
            days_before int(11) NOT NULL,
            refund_percent decimal(5,2) NOT NULL,
            policy_order int(11) DEFAULT 0,
            PRIMARY KEY  (id),
            KEY property_id (property_id)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Commission tracking table
        $table_name = $wpdb->prefix . 'erosity_commissions';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            booking_id bigint(20) NOT NULL,
            vendor_id bigint(20) NOT NULL,
            booking_amount decimal(10,2) NOT NULL,
            commission_amount decimal(10,2) NOT NULL,
            commission_rate decimal(5,2) NOT NULL,
            status varchar(50) NOT NULL,
            stripe_transfer_id varchar(255) DEFAULT NULL,
            paid_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY booking_id (booking_id),
            KEY vendor_id (vendor_id),
            KEY status (status)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Announcements table
        $table_name = $wpdb->prefix . 'erosity_announcements';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            property_id bigint(20) NOT NULL,
            property_type varchar(50) NOT NULL,
            title varchar(255) NOT NULL,
            message text NOT NULL,
            display_from datetime DEFAULT NULL,
            display_until datetime DEFAULT NULL,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY property_id (property_id),
            KEY is_active (is_active)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Email templates table
        $table_name = $wpdb->prefix . 'erosity_email_templates';
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            property_id bigint(20) NOT NULL,
            property_type varchar(50) NOT NULL,
            trigger_action varchar(100) NOT NULL,
            subject varchar(255) NOT NULL,
            message text NOT NULL,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY property_id (property_id),
            KEY trigger_action (trigger_action)
        ) $charset_collate;";
        dbDelta($sql);
        
        // Update database version
        update_option('erosity_db_version', EROSITY_VERSION);
    }
    
    /**
     * Drop all database tables (used on uninstall)
     */
    public static function drop_tables() {
        global $wpdb;
        
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
    }
}
