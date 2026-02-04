<?php
/**
 * Calendar Management
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Calendar Class
 */
class Erosity_Calendar {
    
    /**
     * Initialize
     */
    public static function init() {
        // Calendar initialization
    }
    
    /**
     * Check availability
     *
     * @param int    $property_id   Property ID
     * @param string $property_type Property type
     * @param string $start_date    Start date
     * @param string $end_date      End date
     * @return bool
     */
    public static function check_availability($property_id, $property_type, $start_date, $end_date) {
        global $wpdb;
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}erosity_availability 
            WHERE property_id = %d 
            AND property_type = %s
            AND blocked_date BETWEEN %s AND %s",
            $property_id,
            $property_type,
            $start_date,
            $end_date
        ));
        
        return $count == 0;
    }
    
    /**
     * Block dates
     *
     * @param int    $property_id   Property ID
     * @param string $property_type Property type
     * @param string $start_date    Start date
     * @param string $end_date      End date
     * @param string $reason        Reason for blocking
     * @param int    $booking_id    Booking ID (optional)
     * @return bool
     */
    public static function block_dates($property_id, $property_type, $start_date, $end_date, $reason = '', $booking_id = 0) {
        global $wpdb;
        
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end->modify('+1 day'));
        
        foreach ($period as $date) {
            $wpdb->insert(
                $wpdb->prefix . 'erosity_availability',
                array(
                    'property_id' => $property_id,
                    'property_type' => $property_type,
                    'blocked_date' => $date->format('Y-m-d'),
                    'reason' => $reason,
                    'booking_id' => $booking_id,
                    'is_manual' => $booking_id > 0 ? 0 : 1,
                ),
                array('%d', '%s', '%s', '%s', '%d', '%d')
            );
        }
        
        return true;
    }
    
    /**
     * Unblock dates
     *
     * @param int    $property_id   Property ID
     * @param string $property_type Property type
     * @param string $start_date    Start date
     * @param string $end_date      End date
     * @return bool
     */
    public static function unblock_dates($property_id, $property_type, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}erosity_availability 
            WHERE property_id = %d 
            AND property_type = %s
            AND blocked_date BETWEEN %s AND %s
            AND is_manual = 1",
            $property_id,
            $property_type,
            $start_date,
            $end_date
        ));
    }
    
    /**
     * Get blocked dates
     *
     * @param int    $property_id   Property ID
     * @param string $property_type Property type
     * @param string $start_date    Start date (optional)
     * @param string $end_date      End date (optional)
     * @return array
     */
    public static function get_blocked_dates($property_id, $property_type, $start_date = null, $end_date = null) {
        global $wpdb;
        
        $sql = "SELECT * FROM {$wpdb->prefix}erosity_availability 
                WHERE property_id = %d AND property_type = %s";
        
        $params = array($property_id, $property_type);
        
        if ($start_date && $end_date) {
            $sql .= " AND blocked_date BETWEEN %s AND %s";
            $params[] = $start_date;
            $params[] = $end_date;
        }
        
        return $wpdb->get_results($wpdb->prepare($sql, $params));
    }
    
    /**
     * Generate iCal feed
     *
     * @param int    $property_id   Property ID
     * @param string $property_type Property type
     * @return string
     */
    public static function generate_ical($property_id, $property_type) {
        $blocked_dates = self::get_blocked_dates($property_id, $property_type);
        
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//Erosity//Calendar//EN\r\n";
        
        foreach ($blocked_dates as $block) {
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . md5($block->id) . "@erosity.com\r\n";
            $ical .= "DTSTART:" . date('Ymd', strtotime($block->blocked_date)) . "\r\n";
            $ical .= "DTEND:" . date('Ymd', strtotime($block->blocked_date . ' +1 day')) . "\r\n";
            $ical .= "SUMMARY:Blocked\r\n";
            $ical .= "DESCRIPTION:" . $block->reason . "\r\n";
            $ical .= "END:VEVENT\r\n";
        }
        
        $ical .= "END:VCALENDAR\r\n";
        
        return $ical;
    }
}
