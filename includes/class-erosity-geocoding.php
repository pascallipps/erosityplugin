<?php
/**
 * Geocoding
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Geocoding Class
 */
class Erosity_Geocoding {
    
    /**
     * Initialize
     */
    public static function init() {
        // Geocoding initialization
    }
    
    /**
     * Geocode address
     *
     * @param string $address Full address
     * @return array|false Array with 'lat' and 'lng' or false on failure
     */
    public static function geocode_address($address) {
        $settings = get_option('erosity_settings', array());
        $provider = isset($settings['map_provider']) ? $settings['map_provider'] : 'openstreetmap';
        
        if ($provider === 'google') {
            return self::geocode_google($address);
        }
        
        return self::geocode_nominatim($address);
    }
    
    /**
     * Geocode using Google Maps API
     *
     * @param string $address Full address
     * @return array|false
     */
    private static function geocode_google($address) {
        $settings = get_option('erosity_settings', array());
        $api_key = isset($settings['google_maps_api_key']) ? $settings['google_maps_api_key'] : '';
        
        if (empty($api_key)) {
            return false;
        }
        
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $api_key;
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($body['status'] === 'OK' && !empty($body['results'])) {
            $location = $body['results'][0]['geometry']['location'];
            return array(
                'lat' => $location['lat'],
                'lng' => $location['lng'],
            );
        }
        
        return false;
    }
    
    /**
     * Geocode using Nominatim (OpenStreetMap)
     *
     * @param string $address Full address
     * @return array|false
     */
    private static function geocode_nominatim($address) {
        $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($address) . '&format=json&limit=1';
        
        $response = wp_remote_get($url, array(
            'headers' => array(
                'User-Agent' => 'Erosity WordPress Plugin',
            ),
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!empty($body) && isset($body[0])) {
            return array(
                'lat' => floatval($body[0]['lat']),
                'lng' => floatval($body[0]['lon']),
            );
        }
        
        return false;
    }
    
    /**
     * Geocode city only
     *
     * @param string $city    City name
     * @param string $country Country name
     * @return array|false
     */
    public static function geocode_city($city, $country = '') {
        $address = $city;
        if (!empty($country)) {
            $address .= ', ' . $country;
        }
        
        return self::geocode_address($address);
    }
    
    /**
     * Reverse geocode (get address from coordinates)
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return array|false
     */
    public static function reverse_geocode($lat, $lng) {
        $settings = get_option('erosity_settings', array());
        $provider = isset($settings['map_provider']) ? $settings['map_provider'] : 'openstreetmap';
        
        if ($provider === 'google') {
            return self::reverse_geocode_google($lat, $lng);
        }
        
        return self::reverse_geocode_nominatim($lat, $lng);
    }
    
    /**
     * Reverse geocode using Google Maps API
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return array|false
     */
    private static function reverse_geocode_google($lat, $lng) {
        $settings = get_option('erosity_settings', array());
        $api_key = isset($settings['google_maps_api_key']) ? $settings['google_maps_api_key'] : '';
        
        if (empty($api_key)) {
            return false;
        }
        
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&key=' . $api_key;
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($body['status'] === 'OK' && !empty($body['results'])) {
            return array(
                'formatted_address' => $body['results'][0]['formatted_address'],
            );
        }
        
        return false;
    }
    
    /**
     * Reverse geocode using Nominatim
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return array|false
     */
    private static function reverse_geocode_nominatim($lat, $lng) {
        $url = 'https://nominatim.openstreetmap.org/reverse?lat=' . $lat . '&lon=' . $lng . '&format=json';
        
        $response = wp_remote_get($url, array(
            'headers' => array(
                'User-Agent' => 'Erosity WordPress Plugin',
            ),
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!empty($body) && isset($body['display_name'])) {
            return array(
                'formatted_address' => $body['display_name'],
            );
        }
        
        return false;
    }
}
