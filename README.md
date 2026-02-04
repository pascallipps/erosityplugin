# Erosity WordPress Plugin

A comprehensive WordPress plugin for a rental platform for erotic vacation properties and toys with a commission-based business model.

## Overview

Erosity is a platform where providers can rent out their erotic vacation properties and/or erotic toys. The platform receives a defined commission per mediated accommodation or toy, serving as an intermediary without assuming liability.

## Key Features

- **Multi-step property and toy listing forms** - Comprehensive forms with draft saving
- **User management** - Frontend registration, login, and profile management
- **Age verification** - Required 18+ verification for all users
- **Booking system** - Support for hourly and nightly bookings
- **Calendar management** - Availability tracking with iCal sync
- **Payment integration** - Stripe Connect with split payments
- **Messaging system** - Internal communication with privacy warnings
- **Review system** - 1-5 star ratings with detailed reviews
- **Commission tracking** - Automatic calculation and tracking
- **Admin backend** - Complete management dashboard with statistics
- **Support tickets** - Built-in customer support system

## Structure

```
erosity/
├── erosity.php                 # Main plugin file
├── uninstall.php              # Uninstall cleanup
├── includes/                  # Core classes
│   ├── class-erosity-post-types.php
│   ├── class-erosity-taxonomies.php
│   ├── class-erosity-database.php
│   ├── class-erosity-user.php
│   ├── class-erosity-property.php
│   ├── class-erosity-toy.php
│   ├── class-erosity-booking.php
│   ├── class-erosity-review.php
│   ├── class-erosity-message.php
│   ├── class-erosity-payment.php
│   ├── class-erosity-email.php
│   ├── class-erosity-calendar.php
│   ├── class-erosity-geocoding.php
│   └── class-erosity-coupon.php
├── admin/                     # Admin functionality
│   ├── class-erosity-admin.php
│   ├── class-erosity-admin-settings.php
│   ├── class-erosity-admin-statistics.php
│   └── class-erosity-admin-support.php
├── public/                    # Frontend functionality
│   ├── class-erosity-public.php
│   ├── class-erosity-frontend-auth.php
│   ├── class-erosity-frontend-dashboard.php
│   ├── class-erosity-frontend-forms.php
│   └── class-erosity-frontend-listing.php
├── assets/                    # CSS, JS, images
│   ├── css/
│   │   ├── admin.css
│   │   └── public.css
│   ├── js/
│   │   ├── admin.js
│   │   └── public.js
│   └── images/
├── templates/                 # Template files
└── languages/                 # Translation files

```

## Installation

1. Upload the plugin files to `/wp-content/plugins/erosityplugin/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure settings in the WordPress admin under "Erosity > Settings"
4. Set up Stripe Connect credentials
5. Configure commission rates and other options

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## Documentation

For detailed documentation, see [README_PLUGIN.md](README_PLUGIN.md)

## License

GPL v2 or later