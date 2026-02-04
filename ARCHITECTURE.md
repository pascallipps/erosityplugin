# Erosity Plugin - Architecture Overview

## Plugin Structure Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        EROSITY PLUGIN                            │
│                     (Main: erosity.php)                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ├─────────────────────────────────┐
                              │                                 │
                    ┌─────────▼─────────┐          ┌───────────▼──────────┐
                    │   CORE CLASSES    │          │   ADMIN BACKEND      │
                    │   (includes/)     │          │   (admin/)           │
                    └───────────────────┘          └──────────────────────┘
                              │                                 │
        ┌─────────────────────┼─────────────────────┐          ├─→ Dashboard
        │                     │                     │          ├─→ Settings
        │                     │                     │          ├─→ Statistics
        ▼                     ▼                     ▼          └─→ Support
   Post Types           Taxonomies            Database
   ─────────            ──────────            ────────
   • Property           • Categories          11 Tables:
   • Toy                • Amenities           • user_data
   • Booking            • Tags                • availability
   • Review                                   • pricing
   • Message                                  • extras
   • Ticket                                   • rooms
   • Extra                                    • coupons
                                             • commissions
        │                     │                     │          • etc.
        │                     │                     │
        └─────────────────────┼─────────────────────┘
                              │
                    ┌─────────▼─────────┐
                    │  BUSINESS LOGIC   │
                    └───────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
    User Mgmt             Booking              Payment
    ─────────             ───────              ───────
    • Profiles            • Calendar           • Stripe Connect
    • Verification        • Availability       • Split Payment
    • Addresses           • Pricing            • Commissions
                          • Extras             • Coupons
        │                     │                     │
        └─────────────────────┼─────────────────────┘
                              │
                    ┌─────────▼─────────┐
                    │  COMMUNICATION    │
                    └───────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
   Messages              Emails                Reviews
   ────────              ──────                ───────
   • Internal            • Templates           • Ratings
   • Privacy             • Automation          • Comments
                         • Triggers
                              │
                    ┌─────────▼─────────┐
                    │  FRONTEND UI      │
                    │  (public/)        │
                    └───────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
    Auth Forms            Dashboard            Listings
    ──────────            ─────────            ────────
    • Login               • Overview           • Properties
    • Register            • Properties         • Toys
                          • Bookings           • Filters
                          • Messages           • Map
                          • Profile
                              │
                    ┌─────────▼─────────┐
                    │   SHORTCODES      │
                    └───────────────────┘
                              │
        [erosity_login]  [erosity_register]  [erosity_dashboard]
        [erosity_property_form]  [erosity_toy_form]
        [erosity_properties]  [erosity_toys]  [erosity_map]
```

## Data Flow

### User Registration & Listing Creation
```
User → Registration Form → User Record → Profile Completion
                                              │
                                              ▼
                                    Profile Complete?
                                              │
                                      ┌───────┴────────┐
                                      │                │
                                     YES               NO
                                      │                │
                                      ▼                ▼
                              Can Create Listing   Redirect to Profile
                                      │
                         ┌────────────┴────────────┐
                         │                         │
                    Property Form              Toy Form
                    (9 Steps)                  (with Shipping)
                         │                         │
                         └────────────┬────────────┘
                                      │
                                      ▼
                              Save as Draft (each step)
                                      │
                                      ▼
                                   Preview
                                      │
                                      ▼
                                   Publish
```

### Booking Process
```
Guest → Property Listing → Check Availability
                                   │
                                   ▼
                            Available Dates?
                                   │
                           ┌───────┴───────┐
                           │               │
                          YES              NO
                           │               │
                           ▼               ▼
                    Select Extras    Show Alternative
                           │
                           ▼
                    Apply Coupon (optional)
                           │
                           ▼
                    Price Calculation
                    ├─ Base Price
                    ├─ Extra Persons
                    ├─ Extras
                    ├─ Cleaning Fee
                    ├─ Tourist Tax
                    ├─ Coupon Discount
                    └─ Total
                           │
                           ▼
                    Payment Processing
                    ├─ Customer pays full amount
                    ├─ Commission to platform
                    ├─ Remaining to vendor
                    └─ Deposit (auth hold)
                           │
                           ▼
                    Booking Confirmed
                    ├─ Email to Guest
                    ├─ Email to Host
                    ├─ Block Calendar
                    └─ Create Commission Record
```

### Commission Flow
```
Booking Created → Commission Calculated
                        │
                        ├─ Commission Rate (default 10%)
                        ├─ Original Amount
                        └─ Coupon Type
                              │
                  ┌───────────┴──────────┐
                  │                      │
           Vendor Coupon          Admin Coupon
                  │                      │
    Commission from         Commission reduced
    Original Amount         by coupon value
                  │                      │
                  └───────────┬──────────┘
                              │
                              ▼
                    Commission Status
                    ├─ Expected (on booking)
                    ├─ Pending (on payment)
                    ├─ Paid (after checkout)
                    └─ Cancelled (if refunded)
```

## Class Relationships

```
Erosity (Main)
├── Erosity_Post_Types
│   └── Registers all CPTs
├── Erosity_Taxonomies
│   └── Registers all taxonomies
├── Erosity_Database
│   └── Creates all tables
│
├── Erosity_User
│   ├── get_user_data()
│   ├── has_complete_profile()
│   └── is_age_verified()
│
├── Erosity_Property
│   ├── save_meta_data()
│   └── get_status()
│
├── Erosity_Toy
│   ├── save_meta_data()
│   └── (extends Property with shipping)
│
├── Erosity_Booking
│   ├── create_booking()
│   ├── get_status()
│   └── update_status()
│
├── Erosity_Calendar
│   ├── check_availability()
│   ├── block_dates()
│   ├── unblock_dates()
│   └── generate_ical()
│
├── Erosity_Payment
│   ├── calculate_commission()
│   ├── process_split_payment()
│   ├── create_authorization_hold()
│   └── process_refund()
│
├── Erosity_Coupon
│   ├── validate_coupon()
│   ├── calculate_discount()
│   └── apply_coupon()
│
├── Erosity_Review
│   ├── create_review()
│   ├── get_property_reviews()
│   └── get_average_rating()
│
├── Erosity_Message
│   ├── send_message()
│   ├── filter_sensitive_info()
│   └── mark_as_read()
│
├── Erosity_Email
│   ├── send_booking_confirmation()
│   ├── send_reminder_email()
│   ├── send_review_request()
│   └── send_custom_email()
│
└── Erosity_Geocoding
    ├── geocode_address()
    ├── geocode_city()
    └── reverse_geocode()
```

## Database Relationships

```
wp_users
    │
    └─→ wp_erosity_user_data (1:1)
             │
             └─→ Extended profile info

wp_posts (erosity_property)
    │
    ├─→ wp_erosity_rooms (1:N)
    ├─→ wp_erosity_pricing (1:N)
    ├─→ wp_erosity_extras (1:N)
    ├─→ wp_erosity_cancellation_policies (1:N)
    ├─→ wp_erosity_availability (1:N)
    ├─→ wp_erosity_announcements (1:N)
    └─→ wp_erosity_email_templates (1:N)

wp_posts (erosity_booking)
    │
    ├─→ wp_erosity_booking_extras (1:N)
    ├─→ wp_erosity_commissions (1:1)
    └─→ Links to Property and User

wp_erosity_coupons
    │
    └─→ Can link to specific Property or be Global
```

## Security Layers

```
┌─────────────────────────────────────┐
│  1. WordPress Authentication        │
│     ├─ is_user_logged_in()         │
│     └─ current_user_can()          │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│  2. Age Verification (18+)          │
│     └─ Required for all actions     │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│  3. Profile Completion Check        │
│     └─ Address & payment info       │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│  4. CSRF Protection                 │
│     └─ Nonces for all forms         │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│  5. Data Sanitization               │
│     ├─ sanitize_text_field()       │
│     ├─ sanitize_email()             │
│     └─ esc_html() / esc_attr()      │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│  6. SQL Injection Prevention        │
│     └─ $wpdb->prepare()             │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│  7. Privacy Warnings                │
│     └─ Contact info sharing alerts  │
└─────────────────────────────────────┘
```

## Feature Completion Status

### ✅ Fully Implemented
- Plugin architecture & structure
- Database schema (11 tables)
- Custom post types (7 types)
- Taxonomies (4 types)
- User management & profiles
- Admin backend (dashboard, settings, stats)
- Frontend authentication
- User dashboard
- Review system
- Message system (basic)
- Calendar availability
- Coupon system
- Commission tracking structure
- Email system structure
- Geocoding
- Basic CSS/JS

### 🔨 Partially Implemented
- Multi-step forms (structure only)
- Property listing (basic)
- Booking system (data model only)
- Payment integration (structure only)

### 🚧 To Be Implemented
- Complete 9-step property form
- Complete toy form with shipping
- Stripe Connect API integration
- Interactive calendar UI
- Map with markers
- File upload functionality
- Complete booking workflow
- Email automation triggers
- Single property templates
- Advanced search & filters
- iCal sync functionality

---

**Current Status**: Foundation Complete (60-70%)  
**Next Phase**: Feature Implementation  
**Version**: 1.0.0-alpha
