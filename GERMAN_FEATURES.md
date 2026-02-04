# Erosity Plugin - German Translation & Commission Rate Feature Guide

## Implementierte Änderungen (Implemented Changes)

### 1. Deutsche Oberfläche (German Interface) ✅

Das Plugin ist jetzt vollständig auf Deutsch übersetzt:

#### Post Types (Inhaltstypen)
- **Properties** → **Unterkünfte**
- **Toys** → **Spielzeuge**
- **Bookings** → **Buchungen**
- **Reviews** → **Bewertungen**
- **Messages** → **Nachrichten**
- **Support Tickets** → **Support-Tickets**
- **Extras** → **Extras**

#### Admin-Menü
```
WordPress Backend → Erosity
├── Übersicht (Dashboard)
├── Unterkünfte (Properties)
├── Spielzeuge (Toys)
├── Buchungen (Bookings)
├── Bewertungen (Reviews)
├── Nachrichten (Messages)
├── Support (Support Tickets)
├── Einstellungen (Settings)
└── Statistiken (Statistics)
```

#### Frontend-Übersetzungen
- Alle Formulare auf Deutsch
- Alle Buttons und Labels auf Deutsch
- Alle Fehlermeldungen auf Deutsch
- Alle Beschreibungen auf Deutsch

### 2. Individuelle Provisionssätze pro Benutzer ✅

#### Funktionsweise

**Globale Einstellung:**
```
WordPress Backend → Erosity → Einstellungen
Standard-Provisionssatz (%): [10.00]
```
- Standard: 10%
- Gilt für alle Anbieter
- Kann jederzeit geändert werden

**Benutzerspezifische Einstellung:**
```
WordPress Backend → Benutzer → Profil bearbeiten
Erosity-Einstellungen
├── Individueller Provisionssatz (%): [____]
└── Beschreibung: Leer lassen für globalen Satz (10%)
```

#### Logik-Flow

```
Buchung erstellt
    │
    ├─→ Anbieter-ID ermitteln
    │
    ├─→ Hat Anbieter individuellen Satz?
    │   ├─→ JA: Verwende individuellen Satz
    │   └─→ NEIN: Verwende globalen Satz
    │
    └─→ Provision berechnen
```

#### Beispiele

**Beispiel 1: Globaler Satz**
```
Anbieter: Max Mustermann
Individueller Satz: [leer]
Buchungsbetrag: 100,00 €
→ Provisionssatz: 10% (global)
→ Provision: 10,00 €
→ Auszahlung an Anbieter: 90,00 €
```

**Beispiel 2: Individueller Satz**
```
Anbieter: Anna Schmidt
Individueller Satz: 15%
Buchungsbetrag: 100,00 €
→ Provisionssatz: 15% (individuell)
→ Provision: 15,00 €
→ Auszahlung an Anbieter: 85,00 €
```

**Beispiel 3: Vergünstigter Satz**
```
Anbieter: Premium Partner GmbH
Individueller Satz: 5%
Buchungsbetrag: 100,00 €
→ Provisionssatz: 5% (individuell)
→ Provision: 5,00 €
→ Auszahlung an Anbieter: 95,00 €
```

### 3. Backend-Bearbeitung ✅

Alle Post Types sind vollständig im WordPress-Backend editierbar:

**Unterkünfte (Properties):**
```
Erosity → Unterkünfte
├── Alle Unterkünfte
├── Neue hinzufügen
├── Kategorien
├── Ausstattungsmerkmale
└── Schlagwörter
```

**Spielzeuge (Toys):**
```
Erosity → Spielzeuge
├── Alle Spielzeuge
├── Neues hinzufügen
├── Kategorien
├── Ausstattungsmerkmale
└── Schlagwörter
```

**Buchungen (Bookings):**
```
Erosity → Buchungen
├── Alle Buchungen
└── Bearbeiten (mit allen Details)
```

## Benutzer-Liste mit Provisionssätzen

Im WordPress-Backend unter "Benutzer" erscheint eine neue Spalte:

```
Benutzer | E-Mail | Rolle | Provisionssatz (%)
---------|--------|-------|-------------------
Max Mustermann | max@... | Abonnent | 10,00% (Global)
Anna Schmidt | anna@... | Abonnent | 15,00% (Individuell)
Premium GmbH | info@... | Abonnent | 5,00% (Individuell)
```

## Technische Details

### Datenbankschema

**Tabelle: wp_erosity_user_data**
```sql
CREATE TABLE wp_erosity_user_data (
    ...
    stripe_account_id varchar(255) DEFAULT NULL,
    stripe_account_status varchar(50) DEFAULT NULL,
    commission_rate decimal(5,2) DEFAULT NULL,  -- NEU!
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    ...
);
```

### PHP-Funktionen

**Provisionssatz abrufen:**
```php
// Benutzer-spezifischen oder globalen Satz holen
$rate = Erosity_User::get_commission_rate($user_id);

// Beispiel:
// User hat individuellen Satz: 15%
// User hat KEINEN Satz: 10% (global)
```

**Provisionssatz setzen:**
```php
// Individuellen Satz setzen
Erosity_User::set_commission_rate($user_id, 15.0);

// Zurück zu global (löschen)
Erosity_User::set_commission_rate($user_id, null);
```

**Provision berechnen:**
```php
// Automatisch mit Anbieter-Satz
$commission = Erosity_Payment::calculate_commission(
    $amount = 100.00,
    $rate = null,
    $vendor_id = $user_id
);

// Beispiel-Ausgabe:
// Vendor mit 15%: $commission = 15.00
// Vendor mit global: $commission = 10.00
```

### Übersetzungsdateien

**Dateien:**
- `languages/erosity-de_DE.po` - Textdatei mit Übersetzungen (editierbar)
- `languages/erosity-de_DE.mo` - Binärdatei (kompiliert)

**Anzahl Übersetzungen:** 222 Strings

**Aktivierung:**
WordPress verwendet automatisch die deutschen Übersetzungen, wenn:
1. WordPress auf Deutsch eingestellt ist (Einstellungen → Allgemein → Sprache der Website)
2. Die .mo-Datei im `languages/` Ordner vorhanden ist

## Anwendungsfälle

### Use Case 1: Neuer Standard-Anbieter
```
1. Anbieter registriert sich im Frontend
2. Kein individueller Provisionssatz gesetzt
3. Bei Buchung: 10% Provision (global)
4. Alles automatisch
```

### Use Case 2: Premium-Partner
```
1. Admin erstellt Benutzer oder bearbeitet bestehenden
2. Setzt individuellen Provisionssatz auf 5%
3. Partner bekommt 95% statt 90% pro Buchung
4. Alle zukünftigen Buchungen verwenden 5%
```

### Use Case 3: Hoher Provisionssatz
```
1. Admin bearbeitet Benutzer mit niedriger Qualität
2. Setzt individuellen Provisionssatz auf 20%
3. Benutzer bekommt 80% statt 90% pro Buchung
4. Als Strafe oder für Zusatzservice
```

### Use Case 4: Temporäre Änderung
```
1. Admin ändert globalen Satz von 10% auf 12%
2. Nur Benutzer OHNE individuellen Satz betroffen
3. Benutzer MIT individuellem Satz bleiben unverändert
4. Flexibles Management
```

## Migration für bestehende Datenbank

Wenn das Plugin bereits installiert war, wird beim Update:

1. **Automatisch** das neue Feld `commission_rate` hinzugefügt
2. Alle bestehenden Benutzer haben `NULL` = globaler Satz
3. Keine Daten gehen verloren
4. Sofort einsatzbereit

```sql
-- Wird automatisch ausgeführt bei Plugin-Aktivierung
ALTER TABLE wp_erosity_user_data 
ADD COLUMN commission_rate decimal(5,2) DEFAULT NULL 
AFTER stripe_account_status;
```

## Häufige Fragen (FAQ)

**Q: Wird die Änderung des globalen Satzes auf bestehende Buchungen angewendet?**
A: Nein. Provisionen werden bei Buchungserstellung berechnet und gespeichert.

**Q: Kann ein Benutzer seinen eigenen Provisionssatz sehen/ändern?**
A: Nein. Nur Administratoren können Provisionssätze bearbeiten.

**Q: Was passiert, wenn ich einen individuellen Satz lösche?**
A: Der Benutzer verwendet automatisch wieder den globalen Satz.

**Q: Können Provisionssätze negativ sein?**
A: Nein. Minimum ist 0%, Maximum ist 100%.

**Q: Wie wirkt sich das auf Split-Payments aus?**
A: Die Provision wird VOR dem Split berechnet:
```
Kunde zahlt: 100 €
Provision (15%): 15 € → Plattform
Rest (85 €): 85 € → Anbieter
```

## Zukünftige Erweiterungen

Mögliche Erweiterungen für die Zukunft:

1. **Zeitbasierte Provisionssätze**
   - Unterschiedliche Sätze für Hauptsaison/Nebensaison
   
2. **Kategoriebasierte Provisionssätze**
   - Höhere Provision für Premium-Unterkünfte
   
3. **Staffel-Provisionen**
   - Je mehr Buchungen, desto niedriger die Provision
   
4. **Provisionshistorie**
   - Protokoll aller Provisionssatz-Änderungen

## Support

Bei Fragen oder Problemen:
- Dokumentation: `README_PLUGIN.md`
- Technische Details: `IMPLEMENTATION_SUMMARY.md`
- Architektur: `ARCHITECTURE.md`
