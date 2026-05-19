# Premium Wedding Invitation SaaS Platform

Platform undangan pernikahan digital premium dengan desain elegan, fitur lengkap, dan sistem monetisasi yang terintegrasi.

---

## Overview

Wedding Invite adalah platform SaaS untuk membuat undangan pernikahan digital. Dibangun dengan Laravel 13, Blade + TailwindCSS, dan AlpineJS. Platform ini menyediakan pengalaman premium mulai dari landing page marketing hingga manajemen undangan lengkap.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13 (PHP 8.2+) |
| Frontend | Blade, TailwindCSS, AlpineJS |
| Build | Vite |
| Database | MySQL / SQLite |
| Auth | Laravel Breeze + OTP + Google OAuth |
| Fonts | Playfair Display, Inter, Cormorant Garamond |

---

## Features

### Marketing & Conversion
- Premium marketing landing page (Hero, Stats, Features, Templates, Pricing, FAQ, Testimonials)
- Floating WhatsApp Business CTA button
- SEO system (meta tags, OG/Twitter cards, Schema.org, sitemap.xml, robots.txt)
- Demo invitation showcase page
- Blog/articles system (SEO-ready architecture)
- Trust badges & social proof counters
- Mobile-first responsive design

### Invitation System
- Multiple premium templates (Elegant Luxury, Minimal White, Modern Dark)
- Custom invitation per guest with personalized links
- Multi-event support (Akad, Resepsi, etc.)
- RSVP management with real-time tracking
- QR Code check-in system
- Digital gift/amplop accounts
- Invitation analytics dashboard

### Monetization
- Package system (Basic, Premium, Luxury)
- Order & payment management
- Package limit enforcement (invitations, guests, features)
- Admin payment verification workflow

### Admin Dashboard
- User management (suspend, assign packages)
- Order management (approve, reject, complete)
- Package management (CRUD, toggle active)
- Payment settings management
- Revenue statistics

### Authentication
- Email + password registration
- 6-digit OTP email verification (hashed, 10-min expiry)
- Google OAuth (auto-verified)
- Role-based access (Customer, Admin, SuperAdmin)

---

## Installation

```bash
# Clone the repository
git clone https://github.com/projectarielsa/premium-wedding-invitation.git
cd premium-wedding-invitation

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations & seed
php artisan migrate --seed

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

---

## Configuration

### Google OAuth
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### Email (SMTP)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### WhatsApp Business
Configure in `resources/views/components/marketing/whatsapp-button.blade.php`:
- Default phone: `6281234567890`
- Default message: "Halo, saya tertarik membuat undangan digital premium."

---

## Routes

### Public (Marketing)

| Route | Description |
|-------|-------------|
| `/` | Marketing landing page |
| `/pricing` | Pricing & packages |
| `/demo` | Template demo showcase |
| `/articles` | Blog/articles listing |
| `/articles/{slug}` | Single article |
| `/robots.txt` | SEO robots |
| `/sitemap.xml` | SEO sitemap |
| `/invite/{slug}` | Public invitation view |
| `/invite/{slug}/{guestToken}` | Personalized invitation |

### Authenticated (Dashboard)

| Route | Description |
|-------|-------------|
| `/dashboard` | User dashboard |
| `/invitations` | Manage invitations |
| `/invitations/{id}/guests` | Guest management |
| `/invitations/{id}/analytics` | Invitation analytics |
| `/invitations/{id}/checkin` | QR check-in |
| `/orders` | Order history |
| `/profile` | Profile settings |

### Admin

| Route | Description |
|-------|-------------|
| `/admin` | Admin dashboard |
| `/admin/users` | User management |
| `/admin/orders` | Order management |
| `/admin/packages` | Package management |
| `/admin/payment-settings` | Payment settings |

---

## Testing

```bash
# Run all tests
php artisan test

# Current: 42 tests, 109 assertions — all passing
```

---

## Project Structure

```
app/
├── Enums/              # AttendanceStatus, EventType, UserRole, etc.
├── Exceptions/         # InvitationException, OtpException
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # AdminDashboard, Orders, Packages, Users
│   │   ├── Auth/       # Login, Register, OTP, Google OAuth
│   │   ├── MarketingController.php
│   │   ├── ArticleController.php
│   │   ├── InvitationController.php
│   │   └── ...
│   ├── Middleware/      # OTP, PackageFeature, Admin, Suspended
│   └── Requests/       # Form requests
├── Models/             # User, Invitation, Guest, Package, Article, etc.
├── Services/           # PackageLimitService, SeoService
└── View/Components/    # MarketingLayout

resources/
├── css/app.css         # Premium design system
├── js/app.js           # AlpineJS setup
└── views/
    ├── articles/       # Blog pages
    ├── components/     # Reusable Blade components
    │   ├── marketing/  # Navbar, Footer, WhatsApp button
    │   └── seo-meta.blade.php
    ├── dashboard/      # Dashboard views
    ├── invitations/    # Invitation templates
    ├── layouts/        # App, Guest, Marketing layouts
    └── marketing/      # Landing page, Demo page
```

---

## Design System

### Typography
- **Headings**: Playfair Display (serif, elegant)
- **Body**: Inter (sans-serif, clean)
- **Accent**: Cormorant Garamond (italic, romantic)

### Color Palette
- **Gold**: `#D4AF37` — primary brand color
- **Charcoal**: `#1A1A1A` — dark backgrounds
- **Ivory**: `#FAF8F5` — light backgrounds
- **Rose/Emerald** — accent colors

### Components
- Premium buttons (primary, outline, ghost, danger)
- Card system with shadows
- Form inputs with focus states
- Badge system
- Toast notifications
- Modal system
- Responsive tables

---

## Completed Phases

| Phase | Description | Status |
|-------|-------------|--------|
| 2A | Foundation (Models, Migrations, Enums) | ✅ |
| 2B | Services (PackageLimit, OTP) | ✅ |
| 2C | Controllers & Routes | ✅ |
| 2D | Premium Dashboard UI | ✅ |
| 2E | Public Invitation Templates | ✅ |
| 2F | Advanced Features & Production Readiness | ✅ |
| 3A | Monetization & Business Layer | ✅ |
| 3B | Package Enforcement | ✅ |
| 3C | Authorization Compatibility Fix | ✅ |
| 3D | Marketing Landing Page, SEO & Conversion | ✅ |

---

## Security

- OTP codes hashed with bcrypt (never plain text)
- Rate limiting on auth endpoints
- CSRF protection on all forms
- Package-based feature gating
- Admin role verification middleware
- User suspension system

---

## License

Private — All rights reserved.
