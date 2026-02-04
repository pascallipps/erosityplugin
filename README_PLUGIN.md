# Erosity WordPress Plugin

Eine umfassende Plattform für die Vermietung von erotischen Ferienunterkünften und Spielzeugen mit Provisionsgeschäftsmodell.

## Features

### Core Funktionalität
- **Mehrere Inserat-Typen**: Ferienunterkünfte und Spielzeug-Verleih
- **Provisionsbasiertes Geschäftsmodell**: Automatische Provisionsberechnung und -tracking
- **Multi-step Formulare**: Schrittweise Erstellung von Inseraten
- **Frontend-Verwaltung**: Vollständiges Dashboard für Vermieter und Mieter

### Benutzerverwaltung
- Frontend-Registrierung und Login
- Altersprüfung (18+)
- Erweiterte Benutzerprofile (Adresse, IBAN, BIC, etc.)
- Benutzer-Dashboard mit Übersicht

### Immobilien & Spielzeug
- Custom Post Types für Properties und Toys
- Kategorien und Tags
- Bildergalerien pro Raum
- Ausstattungsmerkmale
- Regeln und Dokumente

### Buchungssystem
- Verfügbarkeitskalender
- Stunden- und Tagesbuchungen
- Flexible Preisgestaltung (Wochentage, Feiertage, Uhrzeiten)
- Extras und Zusatzleistungen
- Gutscheinsystem (Vermieter- und Admin-Gutscheine)

### Zahlungsabwicklung
- Stripe Connect Integration (geplant)
- Split-Payments
- Kautionsverwaltung (Authorization Hold)
- Automatische Erstattungen

### Kommunikation
- Internes Nachrichtensystem
- Automatisierte E-Mails
- Benutzerdefinierte E-Mail-Vorlagen
- Bewertungssystem

### Admin-Backend
- Statistiken und Berichte
- Provisionstracking
- Support-Ticket-System
- Einstellungsverwaltung

## Installation

1. Plugin-Dateien in das `/wp-content/plugins/erosity` Verzeichnis hochladen
2. Plugin über das WordPress-Admin-Panel aktivieren
3. Einstellungen unter "Erosity > Settings" konfigurieren

## Verwendung

### Shortcodes

```
[erosity_login] - Login-Formular
[erosity_register] - Registrierungs-Formular
[erosity_dashboard] - Benutzer-Dashboard
[erosity_property_form] - Immobilien-Formular
[erosity_toy_form] - Spielzeug-Formular
[erosity_properties] - Immobilien-Liste
[erosity_toys] - Spielzeug-Liste
[erosity_map] - Karte mit allen Unterkünften
```

## Datenbank-Struktur

Das Plugin erstellt folgende Custom Tables:
- `erosity_user_data` - Erweiterte Benutzerdaten
- `erosity_availability` - Verfügbarkeitskalender
- `erosity_pricing` - Preisgestaltung
- `erosity_extras` - Zusatzleistungen
- `erosity_rooms` - Raumdaten
- `erosity_coupons` - Gutscheine
- `erosity_booking_extras` - Gebuchte Extras
- `erosity_cancellation_policies` - Stornierungsbedingungen
- `erosity_commissions` - Provisionsabrechnung
- `erosity_announcements` - Ankündigungen
- `erosity_email_templates` - E-Mail-Vorlagen

## Custom Post Types

- `erosity_property` - Ferienunterkünfte
- `erosity_toy` - Spielzeug
- `erosity_booking` - Buchungen
- `erosity_review` - Bewertungen
- `erosity_message` - Nachrichten
- `erosity_ticket` - Support-Tickets
- `erosity_extra` - Extras

## Taxonomies

- `erosity_property_cat` - Immobilien-Kategorien
- `erosity_toy_cat` - Spielzeug-Kategorien
- `erosity_amenity` - Ausstattungsmerkmale
- `erosity_property_tag` - Tags

## Anforderungen

- WordPress 6.0 oder höher
- PHP 7.4 oder höher
- MySQL 5.6 oder höher

## Support

Für Support-Anfragen besuchen Sie bitte [erosity.com](https://erosity.com)

## Changelog

### Version 1.0.0
- Initiales Release
- Grundlegende Plugin-Struktur
- Custom Post Types und Taxonomies
- Datenbank-Schema
- Frontend-Formulare
- Admin-Backend
- Buchungssystem
- Zahlungsintegration (Basis)

## Lizenz

GPL v2 oder später

## Credits

Entwickelt für die Erosity-Plattform
