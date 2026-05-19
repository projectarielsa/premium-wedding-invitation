# Premium Wedding Invitation SaaS - Authentication System

A production-ready authentication system for a Digital Wedding Invitation SaaS Platform.

## Features

### Email OTP Verification
- 6-digit OTP sent via email after registration
- OTP codes are hashed (never stored in plain text)
- 10-minute expiration
- Maximum 5 verification attempts
- 60-second resend cooldown
- Rate limiting on all endpoints

### Google OAuth
- Login/Register with Google
- Auto-verified email for Google users
- Account linking for existing users

## Tech Stack
- Laravel 12
- Laravel Breeze (Blade)
- Laravel Socialite
- TailwindCSS + Vite
- MySQL/SQLite

## Installation

```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start server
php artisan serve
```

## Configuration

### Google OAuth
Get credentials from [Google Cloud Console](https://console.cloud.google.com):
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### Email
Configure SMTP in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

## Routes

| Route | Description |
|-------|-------------|
| `/register` | User registration |
| `/login` | User login |
| `/verify-otp` | OTP verification |
| `/auth/google/redirect` | Google OAuth |
| `/dashboard` | Protected dashboard |

## Security

- OTP hashed with bcrypt
- Rate limiting on auth endpoints
- CSRF protection
- Timing-safe OTP comparison
