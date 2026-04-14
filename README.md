# Vaultscribe 🔐

**Secure Notes Application built with Laravel**

Vaultscribe is a security-focused web application that allows users to safely store and manage personal notes.
It implements multiple security layers such as **Email OTP verification, Two-Factor Authentication (2FA), encrypted note storage, login protection, and activity logging**.

The project is designed as a **secure Laravel application** demonstrating modern authentication and security practices.

---

# Features

### Authentication Security

* User registration with **email OTP verification**
* Secure password hashing using **Laravel Hash**
* **Login rate limiting**
* **Google reCAPTCHA protection** after multiple failed login attempts
* **Password reset via secure email link**

### Two Factor Authentication (2FA)

* Google Authenticator compatible
* QR code setup
* OTP verification challenge
* Enable / Disable 2FA option

### Secure Notes System

* Create notes
* Edit notes
* Delete notes
* Soft delete support
* Trash system
* Restore deleted notes
* Permanently delete notes

### Encryption

All note data is encrypted using Laravel encrypted casts.

* Encrypted note titles
* Encrypted note descriptions
* Encrypted 2FA secrets

### Activity Logging

The system records important security events:

* User login
* User logout
* Note creation
* Note updates
* Note deletion

Each activity log stores:

* User ID
* IP address
* User agent

### Security Features

* OTP expiration
* OTP attempt limits
* Login attempt tracking
* Session regeneration after login
* Route protection with middleware
* Authorization checks for note access
* CSRF protection (Laravel default)

---

# Tech Stack

Backend

* Laravel
* PHP

Frontend

* Blade Templates
* Tailwind CSS
* Livewire

Security

* Google2FA
* Laravel Encryption
* reCAPTCHA

Database

* MySQL / SQLite

---

# Project Structure

```
app/
 ├── Http/Controllers
 │   ├── UserController
 │   ├── NoteController
 │   └── TwoFactorController
 │
 ├── Models
 │   ├── User
 │   ├── Note
 │   └── ActivityLog

routes/
 └── web.php

resources/
 └── views

database/
 └── migrations
```

---

# Installation

Clone repository

```
git clone https://github.com/deepkarmakar-dev/vaultscribe-secure-notes.git
cd vaultscribe-secure-notes
```

Install dependencies

```
composer install
npm install
```

Setup environment

```
cp .env.example .env
php artisan key:generate
```

Configure database in `.env`

Run migrations

```
php artisan migrate
```

Build frontend assets

```
npm run build
```

Run application

```
php artisan serve
```

---

# Security Concepts Implemented

This project demonstrates:

* Secure authentication flow
* OTP verification
* Two-factor authentication
* Data encryption
* Activity auditing
* Brute force protection
* Captcha protection

---

# License

This project is licensed under the **MIT License**.
