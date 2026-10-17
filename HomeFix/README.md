# HomeFix — A Local Services Marketplace
### Web Design & Development (WDD) Mini Project

A full-stack, responsive neighborhood services marketplace built according to the course project presentation (PPT). HomeFix bridges the gap between homeowners and trusted local service professionals (electricians, plumbers, carpenters, deep cleaning, appliance repair, and house painters) across Mumbai.

---

## 🚀 Live Access in XAMPP
- **Web App URL:** `http://localhost/HomeFix/`
- **Root Path:** `C:\xampp\htdocs\HomeFix`
- **MySQL Database:** `homefix_db` (Auto-probed on port **3307** / **3306** with user `root`)

---

## 🔑 Demo Login Accounts

| Role | Portal URL | Email Address | Password | Description |
| :--- | :--- | :--- | :--- | :--- |
| **Homeowner Customer** | `index.php?page=login` | `arun@example.com` | `password123` | Search, compare, book fixers, leave 5-star reviews |
| **Service Pro (Electrician)** | `index.php?page=provider-login` | `ramesh.electrician@example.com` | `password123` | Accept/reject booking requests, track earnings |
| **Service Pro (Plumber)** | `index.php?page=provider-login` | `sunita.plumbing@example.com` | `password123` | Manage plumbing appointments and catalog |
| **Platform Administrator** | `index.php?page=admin-login` | `admin@homefix.com` | `admin123` | Manage users, view stats, remove fake listings |

---

## 🛠️ Complete Technology Stack Alignment

| # | Technology / Requirement | Implementation in HomeFix |
| :--- | :--- | :--- |
| **1** | **XAMPP** | Native structure in `htdocs/HomeFix`, zero external composer dependencies needed, auto-detects MySQL on active port 3307 or 3306. |
| **2** | **HTML5** | Modern semantic structure (`<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`, `<dialog>`), strict form validation with date constraints. |
| **3** | **CSS (Maximalism UI)** | Custom design system with bold typography (`Outfit` + `Plus Jakarta Sans`), high-contrast neo-brutalist borders (`2.5px solid #0F172A`), tactile hard shadows (`4px 4px 0px #0F172A`), vibrant saturated badges, and zero generic boilerplate styling. |
| **4** | **JavaScript** | Debounced live search, interactive star rating picker, dynamic modals, toast notifications, live price recalculator. |
| **5** | **PHP** | Strict object-oriented PHP 8+, clean session management, password hashing (`password_hash`), CSRF tokens. |
| **6** | **MySQL Connectivity** | PDO prepared statements with transactions, auto-database creation, comprehensive schema matching PPT Slide 8. |
| **7** | **XML, DTD & XSL** | Formal `services.xml` catalog validated against `services.dtd` schema and transformed into a web catalog via `services.xsl` stylesheet. |
| **8** | **MVC Architecture** | Strict separation: `app/Models/` (`User`, `Provider`, `Service`, `Booking`, `Review`), `app/Views/`, `app/Controllers/`, and `app/Core/Router.php`. |
| **9** | **AJAX** | Live search without page reload (Slide 7), instant provider Accept/Reject/Complete status updates, and dynamic review submission. |
| **10** | **MapTiler API** | Integrated MapTiler Streets v2 raster & vector tiles with Leaflet.js (replaces Google Maps API per instruction). Displays custom pins for Mumbai areas with interactive popups. |
| **11** | **Separate Logins** | Distinct login & registration workflows for Homeowner Customers (`?page=login`) and Service Providers (`?page=provider-login`). |
| **12** | **Profile Image Gap** | Stylized avatar slot with monogram fallback and explicit "Photo Gap" badge, ready for manual photo drops into `public/uploads/avatars/`. |

---

## 📁 Project Architecture & Directory Layout

```
C:\xampp\htdocs\HomeFix\
├── index.php                 # Main Front Controller & Router Dispatcher
├── .htaccess                 # Apache URL Rewrite rules
├── README.md                 # Project Documentation
├── config/
│   ├── config.php            # App constants, MapTiler key, URLs
│   └── database.php          # Database host and multi-port configuration
├── database/
│   └── schema.sql            # MySQL schema matching PPT Slide 8 with seed data
├── app/
│   ├── Core/
│   │   ├── Database.php      # PDO Singleton with auto-migration & auto-seeding
│   │   ├── Controller.php    # Base controller with layout render, JSON & flash alerts
│   │   ├── Model.php         # Base model with prepared statement wrappers
│   │   ├── Router.php        # Route matcher and dispatcher
│   │   └── Auth.php          # Role-based session authentication manager
│   ├── Models/
│   │   ├── User.php          # Users table model
│   │   ├── Provider.php      # Providers table model with ratings & search
│   │   ├── Service.php       # Services table model
│   │   ├── Booking.php       # Bookings lifecycle model
│   │   └── Review.php        # Reviews & rating recalculation model
│   ├── Controllers/
│   │   ├── HomeController.php     # Homepage, filters, and explore view
│   │   ├── AuthController.php     # Dedicated Customer, Provider & Admin auth
│   │   ├── BookingController.php  # Booking form (matches Slide 6 sketch)
│   │   ├── CustomerController.php # Customer bookings list & review launcher
│   │   ├── ProviderController.php # Pro dashboard, booking actions, catalog
│   │   ├── AdminController.php    # Admin dashboard & fake listing removal
│   │   ├── ApiController.php      # AJAX endpoints for search, status & reviews
│   │   └── XmlController.php      # XML/DTD validation & XSLT transformation
│   └── Views/
│       ├── layouts/               # Header, Navbar, Footer, Flash Alerts
│       ├── home/                  # Index landing page & provider cards
│       ├── auth/                  # Distinct Customer, Provider & Admin login views
│       ├── booking/               # Dedicated booking page & customer dashboard
│       ├── provider/              # Pro dashboard & photo gap editor
│       ├── admin/                 # Admin console & listing moderation
│       └── xml/                   # XML/DTD/XSL interactive explorer
└── public/
    ├── css/
    │   └── style.css              # Maximalism UI stylesheet
    ├── js/
    │   ├── main.js                # Toasts, modals, UI helpers
    │   ├── search-ajax.js         # Debounced AJAX search & category filtering
    │   ├── map-maptiler.js        # Leaflet + MapTiler API mapping
    │   └── booking.js             # Price calculation, AJAX actions & stars
    ├── uploads/
    │   └── avatars/               # Profile photo gap directory
    └── xml/
        ├── services.xml           # Structured XML service catalog
        ├── services.dtd           # Document Type Definition schema
        └── services.xsl           # XSLT stylesheet styling the XML document
```

---

## 🔄 6-Step Booking Flow (Directly from PPT Slide 9)
1. **Search**: Homeowner searches by service category ("Electrician") or Mumbai area ("Andheri").
2. **Compare**: Homeowner compares verified ratings, hourly prices, and past customer reviews.
3. **Book**: Homeowner picks appointment date, time, and service on the dedicated booking form.
4. **Provider Accepts**: Pro reviews the request on their dashboard and accepts it via AJAX.
5. **Completion**: Pro arrives and marks the job as completed.
6. **Review**: Homeowner rates the pro from 1 to 5 stars and writes feedback, which instantly updates the pro's average rating.
