<?php
/**
 * Frontend Listing (Property/Toy listing and map)
 *
 * @package Erosity
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Erosity Frontend Listing Class
 */
class Erosity_Frontend_Listing {
    
    /**
     * Initialize
     */
    public static function init() {
        add_action('wp_ajax_nopriv_erosity_filter_properties', array(__CLASS__, 'filter_properties'));
        add_action('wp_ajax_erosity_filter_properties', array(__CLASS__, 'filter_properties'));
    }
    
    /**
     * Render property list
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_property_list($atts) {
        $atts = shortcode_atts(array(
            'posts_per_page' => 12,
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts);
        
        ob_start();
        ?>
        <div class="erosity-property-listing">
            <div class="erosity-filters">
                <h3><?php _e('Search & Filter', 'erosity'); ?></h3>
                
                <form id="erosity-filter-form" method="get">
                    <p>
                        <label for="search"><?php _e('Search', 'erosity'); ?></label>
                        <input type="text" name="search" id="search" placeholder="<?php esc_attr_e('Search properties...', 'erosity'); ?>">
                    </p>
                    
                    <p>
                        <label for="location"><?php _e('Location', 'erosity'); ?></label>
                        <input type="text" name="location" id="location" placeholder="<?php esc_attr_e('City or postal code', 'erosity'); ?>">
                    </p>
                    
                    <p>
                        <label for="radius"><?php _e('Radius (km)', 'erosity'); ?></label>
                        <select name="radius" id="radius">
                            <option value="10">10 km</option>
                            <option value="25">25 km</option>
                            <option value="50">50 km</option>
                            <option value="100">100 km</option>
                        </select>
                    </p>
                    
                    <p>
                        <label for="booking_type"><?php _e('Booking Type', 'erosity'); ?></label>
                        <select name="booking_type" id="booking_type">
                            <option value=""><?php _e('All', 'erosity'); ?></option>
                            <option value="hourly"><?php _e('Hourly', 'erosity'); ?></option>
                            <option value="nightly"><?php _e('Nightly', 'erosity'); ?></option>
                        </select>
                    </p>
                    
                    <p>
                        <label for="min_price"><?php _e('Min Price (€)', 'erosity'); ?></label>
                        <input type="number" name="min_price" id="min_price" min="0" step="10">
                    </p>
                    
                    <p>
                        <label for="max_price"><?php _e('Max Price (€)', 'erosity'); ?></label>
                        <input type="number" name="max_price" id="max_price" min="0" step="10">
                    </p>
                    
                    <p>
                        <button type="submit" class="erosity-button"><?php _e('Filter', 'erosity'); ?></button>
                        <button type="reset" class="erosity-button-secondary"><?php _e('Reset', 'erosity'); ?></button>
                    </p>
                </form>
            </div>
            
            <div class="erosity-properties-grid" id="erosity-properties-grid">
                <?php self::render_properties_grid($atts); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render properties grid
     *
     * @param array $args Query arguments
     */
    private static function render_properties_grid($args) {
        $query_args = array(
            'post_type' => 'erosity_property',
            'post_status' => 'publish',
            'posts_per_page' => $args['posts_per_page'],
            'orderby' => $args['orderby'],
            'order' => $args['order'],
        );
        
        $properties = new WP_Query($query_args);
        
        if (!$properties->have_posts()) {
            echo '<p>' . __('No properties found.', 'erosity') . '</p>';
            return;
        }
        
        while ($properties->have_posts()) {
            $properties->the_post();
            ?>
            <div class="erosity-property-card">
                <div class="property-image">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php else: ?>
                        <div class="no-image"><?php _e('No image', 'erosity'); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="property-content">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    
                    <div class="property-meta">
                        <span class="location">
                            <?php echo esc_html(get_post_meta(get_the_ID(), '_city', true)); ?>
                        </span>
                        <span class="rating">
                            <?php 
                            $rating = Erosity_Review::get_average_rating(get_the_ID());
                            echo '★ ' . $rating . '/5';
                            ?>
                        </span>
                    </div>
                    
                    <div class="property-excerpt">
                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                    </div>
                    
                    <div class="property-price">
                        <span class="from"><?php _e('From', 'erosity'); ?></span>
                        <span class="amount"><?php echo esc_html(get_post_meta(get_the_ID(), '_base_price', true)); ?> €</span>
                        <span class="unit"><?php _e('/ night', 'erosity'); ?></span>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="erosity-button">
                        <?php _e('View Details', 'erosity'); ?>
                    </a>
                </div>
            </div>
            <?php
        }
        
        wp_reset_postdata();
    }
    
    /**
     * Render toy list
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_toy_list($atts) {
        $atts = shortcode_atts(array(
            'posts_per_page' => 12,
        ), $atts);
        
        ob_start();
        ?>
        <div class="erosity-toy-listing">
            <h2><?php _e('Available Toys', 'erosity'); ?></h2>
            <!-- Toy listing implementation -->
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render map
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public static function render_map($atts) {
        $atts = shortcode_atts(array(
            'height' => '500px',
            'zoom' => 10,
        ), $atts);
        
        ob_start();
        ?>
        <div class="erosity-map-container">
            <div id="erosity-map" style="height: <?php echo esc_attr($atts['height']); ?>;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Filter properties via AJAX
     */
    public static function filter_properties() {
        check_ajax_referer('erosity_nonce', 'nonce');
        
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $location = isset($_POST['location']) ? sanitize_text_field($_POST['location']) : '';
        $booking_type = isset($_POST['booking_type']) ? sanitize_text_field($_POST['booking_type']) : '';
        $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
        $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : 0;
        
        $args = array(
            'post_type' => 'erosity_property',
            'post_status' => 'publish',
            'posts_per_page' => 12,
        );
        
        if ($search) {
            $args['s'] = $search;
        }
        
        $meta_query = array('relation' => 'AND');
        
        if ($min_price > 0) {
            $meta_query[] = array(
                'key' => '_base_price',
                'value' => $min_price,
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }
        
        if ($max_price > 0) {
            $meta_query[] = array(
                'key' => '_base_price',
                'value' => $max_price,
                'compare' => '<=',
                'type' => 'NUMERIC',
            );
        }
        
        if (!empty($meta_query) && count($meta_query) > 1) {
            $args['meta_query'] = $meta_query;
        }
        
        ob_start();
        self::render_properties_grid($args);
        $html = ob_get_clean();
        
        wp_send_json_success(array('html' => $html));
    }
}
