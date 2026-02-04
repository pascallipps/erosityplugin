# Erosity Plugin - Installation & Setup Guide

## Quick Start

### Installation

1. **Download or Clone**
   ```bash
   cd /path/to/wordpress/wp-content/plugins/
   git clone https://github.com/pascallipps/erosityplugin.git erosity
   ```

2. **Activate Plugin**
   - Go to WordPress Admin → Plugins
   - Find "Erosity" in the list
   - Click "Activate"

3. **Verify Installation**
   - Check that custom tables were created in your database
   - Look for tables with prefix `wp_erosity_*`
   - A new "Erosity" menu should appear in WordPress Admin

### Initial Configuration

1. **Go to Erosity → Settings**
   - Set commission rate (default: 10%)
   - Choose currency (EUR or USD)
   - Configure Stripe mode (Test/Live)
   - Enable/disable age verification
   - Select map provider (OpenStreetMap or Google Maps)

2. **Add Categories**
   - Go to Properties → Categories
   - Add property categories (e.g., "Apartment", "House", "Suite")
   - Go to Toys → Categories  
   - Add toy categories

3. **Add Amenities**
   - Go to Properties → Amenities
   - Add common amenities (e.g., "WLAN", "Parking", "Kitchen")

### Create Frontend Pages

Create the following WordPress pages and add shortcodes:

1. **Login Page** (`/login`)
   ```
   [erosity_login]
   ```

2. **Registration Page** (`/register`)
   ```
   [erosity_register]
   ```

3. **Dashboard Page** (`/dashboard`)
   ```
   [erosity_dashboard]
   ```

4. **Add Property Page** (`/add-property`)
   ```
   [erosity_property_form]
   ```

5. **Add Toy Page** (`/add-toy`)
   ```
   [erosity_toy_form]
   ```

6. **Properties Listing** (`/properties`)
   ```
   [erosity_properties]
   ```

7. **Toys Listing** (`/toys`)
   ```
   [erosity_toys]
   ```

8. **Map View** (`/map`)
   ```
   [erosity_map]
   ```

### Configure Permalinks

1. Go to Settings → Permalinks
2. Select "Post name" or any custom structure
3. Click "Save Changes" to flush rewrite rules

### Test the Plugin

1. **Create a Test User**
   - Go to the registration page
   - Create a new account
   - Complete the profile in the dashboard

2. **Add a Test Property**
   - Go to the "Add Property" page
   - Fill in the multi-step form
   - Save as draft

3. **Check Admin Dashboard**
   - Go to Erosity → Dashboard
   - Verify statistics are displayed
   - Check that your test property appears

## Advanced Configuration

### Stripe Connect Setup

1. Create a Stripe account at https://stripe.com
2. Get your API keys from Stripe Dashboard
3. Go to Erosity → Settings
4. Enter your Stripe keys:
   - Test Publishable Key
   - Test Secret Key
   - Live Publishable Key (for production)
   - Live Secret Key (for production)

### Google Maps Setup (Optional)

If using Google Maps instead of OpenStreetMap:

1. Get a Google Maps API key from Google Cloud Console
2. Enable these APIs:
   - Maps JavaScript API
   - Geocoding API
3. Go to Erosity → Settings
4. Select "Google Maps" as map provider
5. Enter your API key

### Email Configuration

1. Go to Erosity → Settings
2. Set "Email From Name" (e.g., "Erosity Support")
3. Set "Email From Address" (e.g., "noreply@erosity.com")

### Default Cancellation Policy

In your database, you can set default cancellation policies:

```php
$default_policy = array(
    array('days' => 30, 'refund' => 100),  // 100% refund if cancelled 30+ days before
    array('days' => 7, 'refund' => 50),    // 50% refund if cancelled 7-30 days before
    array('days' => 0, 'refund' => 0)      // No refund if cancelled <7 days before
);
```

## Database Tables Reference

After activation, the following tables are created:

| Table Name | Purpose |
|------------|---------|
| `wp_erosity_user_data` | Extended user information |
| `wp_erosity_availability` | Calendar availability |
| `wp_erosity_pricing` | Property pricing rules |
| `wp_erosity_extras` | Additional services |
| `wp_erosity_rooms` | Room details |
| `wp_erosity_coupons` | Discount codes |
| `wp_erosity_booking_extras` | Booked extras |
| `wp_erosity_cancellation_policies` | Cancellation rules |
| `wp_erosity_commissions` | Commission tracking |
| `wp_erosity_announcements` | Property announcements |
| `wp_erosity_email_templates` | Custom email templates |

## Custom Post Types

The plugin registers these post types:

- `erosity_property` - Vacation properties
- `erosity_toy` - Toys for rent
- `erosity_booking` - Booking records
- `erosity_review` - Reviews and ratings
- `erosity_message` - Internal messages
- `erosity_ticket` - Support tickets
- `erosity_extra` - Extra services

## Taxonomies

- `erosity_property_cat` - Property categories
- `erosity_toy_cat` - Toy categories
- `erosity_amenity` - Amenities
- `erosity_property_tag` - Property tags

## Troubleshooting

### Plugin doesn't activate
- Check PHP version (minimum 7.4)
- Check WordPress version (minimum 6.0)
- Check error logs in `wp-content/debug.log`

### Tables not created
- Check database permissions
- Try deactivating and reactivating the plugin
- Check for database errors in debug log

### 404 on custom post types
- Go to Settings → Permalinks
- Click "Save Changes" to flush rewrite rules

### Styles not loading
- Clear browser cache
- Check that asset files exist in `assets/` directory
- Verify plugin URL is correct

## Security Recommendations

1. **Use HTTPS** - Always use SSL for production
2. **Strong passwords** - Enforce strong password policy
3. **Regular updates** - Keep WordPress and plugin updated
4. **Backup regularly** - Backup database and files
5. **Test mode** - Use Stripe test mode during development
6. **Age verification** - Keep age verification enabled

## Support & Documentation

- Full documentation: `README_PLUGIN.md`
- Implementation details: `IMPLEMENTATION_SUMMARY.md`
- Repository: https://github.com/pascallipps/erosityplugin

## Uninstallation

To completely remove the plugin:

1. Deactivate the plugin
2. Delete the plugin files
3. The `uninstall.php` script will automatically:
   - Delete all custom post types and their data
   - Drop all custom database tables
   - Remove all plugin options
   - Clean up transients

⚠️ **Warning**: Uninstalling will permanently delete all plugin data. Backup first!

## Next Development Steps

This is the foundational structure. To complete the plugin:

1. Implement all 9 steps of the property form
2. Complete Stripe Connect integration
3. Add interactive calendar UI
4. Implement map with property markers
5. Add file upload functionality
6. Complete email automation
7. Create single property view templates
8. Add comprehensive testing

---

**Version**: 1.0.0  
**Status**: Foundation Complete  
**License**: GPL v2 or later
