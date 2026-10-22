# Moneyguru Financial Services

A complete, production-grade financial advisory web application built with **PHP 8.2**, **MySQL (XAMPP)**, and **Tailwind CSS**, based on the design and specifications of [Moneyguru Financial Services](https://moneyguru-financial-services.ai.studio/).

---

## 🌟 Key Features

1. **Modern Tailwind CSS Design System**:
   - Custom palette tailored to Moneyguru:
     - Off-white background (`#F7F8F5`)
     - Dark Slate Blue headings (`#092532`)
     - Deep Navy sections & navbar (`#071D29`, `#0B2733`, `#123B43`)
     - Neon Chartreuse / Lime CTA accents (`#C9F24A`, `#D9F77A`)
   - Clean geometric typography via **Plus Jakarta Sans** and **Lucide Icons**.

2. **Full Dynamic Frontend (`index.php`)**:
   - **Sticky Glassmorphism Navbar**: Responsive with smooth scroll, mobile drawer menu, and quick action buttons.
   - **Hero Section**: High-impact value proposition, trust ratings, and an interactive smartphone/tablet mockup featuring dynamic tabs (`Asset Allocation` vs `Planned Milestones`).
   - **Real-Time Market Ticker Ribbon**: Continuous auto-scrolling ticker with Indian benchmarks and commodities (NIFTY 50, SENSEX, BANK NIFTY, NIFTY IT, NIFTY MIDCAP, GOLD 24K, 10Y G-SEC, USD/INR, BRENT CRUDE) and live price tick simulation.
   - **Visual Quick Overview**: 4 interactive card categories linking to services and consultations.
   - **About Founder**: Dedicated section for **Saswata Roy**, AMFI-Registered Mutual Fund Distributor (**ARN- 136048**), with photo, credentials, and advisory philosophy.
   - **Stats Bar**: 4 highlight metrics (7+ Categories, 1 Unified Destination, 100% Beginner Friendly, 360° Guidance).
   - **7 Core Financial Services**: With interactive category filter tabs (`All`, `Invest`, `Protect`, `Borrow`), detailed cards, badges, and modal triggers.
   - **Interactive Financial Calculators**:
     - **SIP Growth Calculator**: Sliders for Monthly Investment, Time Horizon, Expected Annual Return rate. Real-time compounding formula with Indian Rupee (`₹`) formatting.
     - **Loan EMI Calculator**: Sliders for Loan Amount, Tenure, and Interest Rate. Real-time monthly EMI, total interest, and total payable calculations.
   - **Why Choose Moneyguru**: Feature comparison and client-first fiduciary standards.
   - **4-Step Beginner Journey**: Step 1 Goals &rarr; Step 2 Options &rarr; Step 3 Trade-offs &rarr; Step 4 Move Forward.
   - **Educational Insights & Guides**: Full-length articles with read times and interactive reader modal.
   - **Partner With Us Banner**: Collaborative offerings for corporate HRs, CAs, and affiliates.
   - **Direct Contact & Consultation Booking**: Direct calling (`6291390883`), WhatsApp integration, and an instant AJAX consultation booking form.
   - **Interactive FAQ Accordion**: Real-time keyword search bar and category filters.
   - **Final High-Converting CTA Banner**: Inviting users to schedule a consultation.
   - **Footer**: Detailed service directory, AMFI ARN- 136048 disclaimer, SEBI regulatory notices, and policy modals.

3. **Interactive Modals**:
   - **Service Detail Modal**: Deep dive into benefits, beginner guidance, and regulatory risk notes.
   - **Article Reader Modal**: Full-text article reader with key takeaways and author bio.
   - **Partner Proposal Modal**: Structured form saving institutional proposals into MySQL.
   - **Quick Consultation Modal**: Popup booking accessible from anywhere on the page.
   - **Client Portal Modal**: Allows clients to look up their booked consultations and status by email or phone.
   - **Legal Modals**: Privacy Policy, Terms of Service, and Regulatory Disclosures.

4. **MySQL Database & Backend APIs (`api/`)**:
   - `api/book_consultation.php`: Inserts client consultations into `consultations` table with validation and generates reference codes (e.g. `MG-00001`).
   - `api/partner_inquiry.php`: Inserts partner proposals into `partner_inquiries` table.
   - `api/get_my_consultations.php`: Looks up client bookings by email or phone.
   - `api/save_calculator.php`: Logs calculator usage.

5. **Complete Admin Management Panel (`admin/`)**:
   - **Authentication**: Secure login with bcrypt hashed passwords.
   - **Dashboard (`admin/index.php`)**: Live counters for total leads, pending actions, scheduled sessions, and completed consultations.
   - **Consultations Management (`admin/consultations.php`)**: Filter by status, search by client, update advisor notes, change lead status, and **Export to CSV**.
   - **Partner Inquiries (`admin/partners.php`)**: Review corporate proposals and change approval statuses.
   - **Settings (`admin/settings.php`)**: Update phone number, WhatsApp link, ARN number, founder name, and change admin password.

---

## 🚀 How to Run in XAMPP

1. **Verify XAMPP Services**:
   - Open **XAMPP Control Panel** and ensure both **Apache** and **MySQL** are running (green).

2. **Access the Web Application**:
   - Open your browser and navigate to:
     ```
     http://localhost/Moneyguru/
     ```

3. **Access the Admin Panel**:
   - Navigate to:
     ```
     http://localhost/Moneyguru/admin/login.php
     ```
   - **Initial Administrator Account**:
     - **Email:** `saswata@moneyguru.co.in`
     - **Password:** `admin123`
   - *Note: Default credentials are not exposed on the login interface in production mode.*

4. **Admin Profile & Password Management**:
   - Navigate to the **Profile** tab (`http://localhost/Moneyguru/admin/profile.php`).
   - Here the administrator can update their **Admin Full Name**, **Login Email Address**, and **Password** (with current password verification).

5. **SMTP Email Gateway**:
   - Configure your outgoing mail server in **Settings &rarr; SMTP Email Configuration** (`http://localhost/Moneyguru/admin/settings.php`).
   - Supports **Gmail** (Port 587 + TLS + App Password), **Google Workspace**, **Brevo/Sendinblue**, **Outlook**, and standard cPanel webmail.
   - Includes a **Live Connection Test Tool** with real-time socket and authentication diagnostic logs.
   - Automatically dispatches:
     - Client consultation booking confirmations (`MG-XXXXX` reference).
     - Instant advisor lead alert emails.
     - Partner proposal confirmations.

6. **Production Security Architecture**:
   - **CSRF Token Validation**: Enforced across all admin mutation requests (profile, settings, status updates, deletions).
   - **Brute-Force Rate Limiting**: Automatic 15-minute IP/account lockout after 5 consecutive failed login attempts.
   - **Session Integrity**: Inactivity timeout (2 hours) and User-Agent fingerprint verification to protect against session hijacking.
   - **Zero Leaks**: Login form inputs are never pre-filled, and credential hints are stripped from HTML source.
   - **Anti-Spam Honeypots**: Hidden traps filter automated form spam bots on public consultation and partnership APIs.
   - **Apache Hardening (`.htaccess`)**: Directory indexing disabled, sensitive configuration and hidden files protected, compression and asset caching enabled.

7. **Database Configuration**:
   - Connected automatically to MySQL in XAMPP via PDO:
     - **Host:** `localhost` (or `DB_HOST` env var)
     - **Database:** `moneyguru` (or `DB_NAME` env var)
     - **User:** `root` (or `DB_USER` env var)
     - **Password:** *(empty or `DB_PASS` env var)*
   - Database tables were initialized and seeded automatically by `setup_db.php`.

---

## 📁 Directory Structure

```
c:\xampp\htdocs\Moneyguru\
├── .htaccess                  # Apache production security, caching & compression
├── admin\                     # Admin Dashboard & Management Portal
│   ├── auth_check.php         # Admin session protector with timeout & hijacking check
│   ├── consultations.php      # Consultations table & CSV export with CSRF validation
│   ├── index.php              # Dashboard with KPI cards & CSRF quick actions
│   ├── login.php              # Hardened Email + Password Admin login (rate-limited)
│   ├── logout.php             # Session destroy
│   ├── partners.php           # Partner proposals management with CSRF protection
│   ├── profile.php            # Admin Profile (change email & password)
│   └── settings.php           # Business coordinates & SMTP gateway settings
├── api\                       # Backend JSON REST endpoints
│   ├── book_consultation.php  # Handles consultation bookings + honeypot + SMTP alerts
│   ├── get_my_consultations.php# Client portal lookup with privacy protection
│   ├── partner_inquiry.php    # Handles institutional partner proposals + honeypot + SMTP
│   └── save_calculator.php    # Calculator usage telemetry
├── config\
│   ├── .htaccess              # Denies direct browser access to configuration files
│   ├── db.php                 # Production PDO MySQL connection with ENV support
│   ├── security.php           # Central CSRF, session hardening, and rate-limiting
│   └── smtp.php               # Standalone native socket SMTP Mailer class
├── assets\
│   ├── css\
│   │   └── style.css          # Pre-compiled Tailwind CSS v4 stylesheet
│   ├── img\
│   │   ├── about-portrait.jpg # Advisor portrait photograph
│   │   ├── logo.svg           # Moneyguru brand vector logo
│   │   └── saswata-roy.svg    # Advisor vector portrait fallback
│   └── js\
│       └── main.js            # Interactive calculators, modals, & AJAX handlers
├── includes\                  # Reusable PHP component views
│   ├── about_founder.php      # Saswata Roy bio & AMFI credentials
│   ├── articles.php           # Financial insights articles
│   ├── calculators.php        # SIP & Loan EMI interactive calculators
│   ├── contact.php            # Direct consultation booking form
│   ├── faqs.php               # Searchable FAQ accordion
│   ├── final_cta.php          # High-converting final CTA banner
│   ├── footer.php             # Full regulatory footer
│   ├── header.php             # HTML head, fonts, & Tailwind config
│   ├── helpers.php            # Database query helper functions
│   ├── hero.php               # Hero section with interactive mockup
│   ├── modals.php             # All 6 modal dialogs
│   ├── navbar.php             # Sticky glassmorphic navbar
│   ├── overview.php           # 4 quick category highlight cards
│   ├── partner_banner.php     # Partner With Moneyguru banner
│   ├── process.php            # 4-step beginner financial roadmap
│   ├── services.php           # 7 financial service cards with filters
│   ├── stats.php              # 4 key performance metrics
│   ├── ticker.php             # Live market index & commodity ticker
│   └── why_us.php             # Why Choose Us comparison section
├── articles.json              # Seed data for financial articles
├── faqs.json                  # Seed data for FAQs
├── index.php                  # Main public entry point
├── services.json              # Seed data for 7 financial services
├── setup_db.php               # Database table generator & seeder
└── ticker.json                # Seed data for market indices
```

# Moneyguru-php-Sql
"# Moneyguru-php-Sql" 
