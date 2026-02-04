# UI Mockups - German Interface & Commission Rates

## 1. WordPress Backend - German Menu

```
┌─────────────────────────────────────────────────────────┐
│ WordPress Admin                                         │
├─────────────────────────────────────────────────────────┤
│ Dashboard                                               │
│ ├─ Beiträge                                             │
│ ├─ Medien                                               │
│ ├─ Seiten                                               │
│ ├─ Kommentare                                           │
│ ├─ Erosity                                 ◄────────────┤
│ │  ├─ Übersicht                                         │
│ │  ├─ Unterkünfte                                       │
│ │  ├─ Spielzeuge                                        │
│ │  ├─ Buchungen                                         │
│ │  ├─ Bewertungen                                       │
│ │  ├─ Nachrichten                                       │
│ │  ├─ Support                                           │
│ │  ├─ Einstellungen                                     │
│ │  └─ Statistiken                                       │
│ ├─ Benutzer                                             │
│ └─ Einstellungen                                        │
└─────────────────────────────────────────────────────────┘
```

## 2. Settings Page - Commission Rate

```
┌─────────────────────────────────────────────────────────────────┐
│ Erosity › Einstellungen                                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Standard-Provisionssatz (%)                                    │
│  ┌────────┐                                                     │
│  │  10.00 │                                                     │
│  └────────┘                                                     │
│  Globaler Provisionssatz für alle Anbieter. Einzelne           │
│  Anbieter können dies in ihrem Profil überschreiben.           │
│                                                                 │
│  Währung                                                        │
│  ┌──────────┐                                                  │
│  │ EUR    ▼ │                                                  │
│  └──────────┘                                                  │
│                                                                 │
│  Stripe-Modus                                                   │
│  ┌──────────┐                                                  │
│  │ Test   ▼ │                                                  │
│  └──────────┘                                                  │
│                                                                 │
│  [ Einstellungen speichern ]                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 3. User Profile - Custom Commission Rate

```
┌─────────────────────────────────────────────────────────────────┐
│ Profil bearbeiten - Max Mustermann                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Benutzername                                                   │
│  max.mustermann                                                 │
│                                                                 │
│  E-Mail                                                         │
│  max@example.com                                                │
│                                                                 │
│  ═══════════════════════════════════════════════════════════   │
│  Erosity-Einstellungen                                          │
│  ───────────────────────────────────────────────────────────   │
│                                                                 │
│  Individueller Provisionssatz (%)                               │
│  ┌────────┐                                                     │
│  │        │  ◄── Leer = globaler Satz (10%)                    │
│  └────────┘                                                     │
│  Leer lassen, um den globalen Provisionssatz (10%) zu          │
│  verwenden. Setzen Sie einen individuellen Satz, um die        │
│  globale Einstellung für diesen Benutzer zu überschreiben.     │
│                                                                 │
│  [ Profil aktualisieren ]                                       │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 4. User Profile - With Custom Rate

```
┌─────────────────────────────────────────────────────────────────┐
│ Profil bearbeiten - Anna Schmidt                                │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ═══════════════════════════════════════════════════════════   │
│  Erosity-Einstellungen                                          │
│  ───────────────────────────────────────────────────────────   │
│                                                                 │
│  Individueller Provisionssatz (%)                               │
│  ┌────────┐                                                     │
│  │  15.00 │  ◄── Individueller Satz gesetzt                    │
│  └────────┘                                                     │
│  Leer lassen, um den globalen Provisionssatz (10%) zu          │
│  verwenden. Setzen Sie einen individuellen Satz, um die        │
│  globale Einstellung für diesen Benutzer zu überschreiben.     │
│                                                                 │
│  [ Profil aktualisieren ]                                       │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 5. Users List - Commission Rate Column

```
┌────────────────────────────────────────────────────────────────────────────┐
│ Benutzer                                              [ Neu hinzufügen ]    │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                            │
│  Suchen [ ______________ ]  [ Benutzer durchsuchen ]                       │
│                                                                            │
│  ┌──────────────────┬───────────────────┬────────┬────────────────────┐   │
│  │ Benutzername     │ E-Mail            │ Rolle  │ Provisionssatz (%) │   │
│  ├──────────────────┼───────────────────┼────────┼────────────────────┤   │
│  │ max.mustermann   │ max@example.com   │ Autor  │ 10.00% (Global)    │   │
│  │ anna.schmidt     │ anna@example.com  │ Autor  │ 15.00% (Individuell)│  │
│  │ premium.partner  │ info@premium.de   │ Autor  │ 5.00% (Individuell) │  │
│  │ john.doe         │ john@example.com  │ Autor  │ 10.00% (Global)    │   │
│  └──────────────────┴───────────────────┴────────┴────────────────────┘   │
│                                                                            │
└────────────────────────────────────────────────────────────────────────────┘
```

## 6. Property Edit - German Interface

```
┌─────────────────────────────────────────────────────────────────┐
│ Unterkunft bearbeiten                                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Titel                                                          │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ Luxus-Suite mit Whirlpool                                 │ │
│  └───────────────────────────────────────────────────────────┘ │
│                                                                 │
│  Beschreibung                                                   │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │                                                           │ │
│  │ Eine wunderschöne Suite mit allem Komfort...             │ │
│  │                                                           │ │
│  └───────────────────────────────────────────────────────────┘ │
│                                                                 │
│  Kategorien                                                     │
│  □ Apartment                                                    │
│  ☑ Suite                                                        │
│  □ Zimmer                                                       │
│                                                                 │
│  [ Veröffentlichen ]  [ Entwurf speichern ]                     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 7. Frontend - Property Listing (German)

```
┌─────────────────────────────────────────────────────────────────┐
│                    EROSITY                                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Suche & Filter                                                 │
│  ┌────────────────────┐                                         │
│  │ Suchen...          │                                         │
│  └────────────────────┘                                         │
│                                                                 │
│  Standort                                                       │
│  ┌────────────────────┐                                         │
│  │ Stadt oder PLZ     │                                         │
│  └────────────────────┘                                         │
│                                                                 │
│  Umkreis (km)                                                   │
│  ┌──────────┐                                                  │
│  │ 50 km  ▼ │                                                  │
│  └──────────┘                                                  │
│                                                                 │
│  [ Filtern ]  [ Zurücksetzen ]                                  │
│                                                                 │
│  ═══════════════════════════════════════════════════════════   │
│                                                                 │
│  Unterkünfte (12 gefunden)                                      │
│                                                                 │
│  ┌─────────────────┬─────────────────┬─────────────────┐       │
│  │ [Bild]          │ [Bild]          │ [Bild]          │       │
│  │                 │                 │                 │       │
│  │ Luxus-Suite     │ Romantic Room   │ Party-Loft      │       │
│  │ München         │ Hamburg         │ Berlin          │       │
│  │ ★★★★★ 4.8      │ ★★★★☆ 4.2      │ ★★★★★ 4.9      │       │
│  │                 │                 │                 │       │
│  │ Ab 89€ / Nacht  │ Ab 65€ / Nacht  │ Ab 120€ / Nacht │       │
│  │ [Details]       │ [Details]       │ [Details]       │       │
│  └─────────────────┴─────────────────┴─────────────────┘       │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 8. Booking Flow - Commission Calculation

```
┌─────────────────────────────────────────────────────────────────┐
│ Buchungsübersicht                                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Unterkunft:   Luxus-Suite mit Whirlpool                        │
│  Anbieter:     Anna Schmidt                                     │
│  Zeitraum:     15.03.2024 - 17.03.2024 (2 Nächte)              │
│                                                                 │
│  ───────────────────────────────────────────────────────────   │
│  Preisberechnung                                                │
│  ───────────────────────────────────────────────────────────   │
│                                                                 │
│  Grundpreis (2 Nächte à 89€)              178,00 €              │
│  Extra: Sekt-Frühstück                     25,00 €              │
│  Endreinigung                              30,00 €              │
│                                          ─────────              │
│  Zwischensumme                            233,00 €              │
│                                                                 │
│  Kurtaxe (2 Personen, 2 Nächte)             8,00 €              │
│  MwSt. (19%)                               44,27 €              │
│                                          ─────────              │
│  Gesamtpreis                              285,27 €              │
│                                          ═════════              │
│                                                                 │
│  Backend-Berechnung (für Admin):                                │
│  ─────────────────────────────────────────                     │
│  Buchungsbetrag:                          233,00 €              │
│  Provision (15% - Anna Schmidt):           34,95 €              │
│  Auszahlung an Anbieter:                  198,05 €              │
│                                                                 │
│  [ Zahlungspflichtig buchen ]                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## Summary of Changes

### Visual Changes:
1. ✅ All menu items in German
2. ✅ All form labels in German
3. ✅ All buttons in German
4. ✅ All help text in German

### Functional Changes:
1. ✅ Commission rate field in user profile
2. ✅ Commission rate column in users list
3. ✅ Individual rate overrides global rate
4. ✅ Automatic calculation in payment flow

### Backend Access:
1. ✅ All post types under "Erosity" menu
2. ✅ Full editing capabilities
3. ✅ German taxonomy names
4. ✅ German column headers
