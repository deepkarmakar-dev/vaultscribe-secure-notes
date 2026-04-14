# 🔐 VaultScribe – Secure Notes Application

VaultScribe is a **security-focused note-taking web application** built with Laravel.
It combines strong authentication, encryption, and modern security practices to protect user data.

---

## 🚀 Features

### 🔑 Authentication & Security

* Secure user registration with:

  * Strong password policy (uppercase, lowercase, number, special char)
  * Weak password detection (common passwords blocked)
* Password hashing with **Argon2id + Pepper**
* Email verification via OTP
* Login protection with:

  * Rate limiting (session-based attempts)
  * Google reCAPTCHA after multiple failed attempts

---

### 🔐 Two-Factor Authentication (2FA)

* Google Authenticator integration
* Encrypted 2FA secret storage
* OTP verification during login
* Enable / Disable anytime

---

### 📝 Notes System

* Create, update, delete notes
* Soft delete (Trash system)
* Restore / permanently delete notes
* Auto encryption of:

  * Title
  * Description

---

### 📜 Activity Logging

Tracks user actions:

* Login
* Note creation
* Note update
* Note deletion

Includes:

* IP address
* User agent

---

### 🔒 Advanced Security

* Content Security Policy (CSP)
* XSS, Clickjacking, MIME protection
* HSTS (production)
* Secure headers middleware
* Encrypted sensitive fields
* CSRF protection

---

### 🔁 Password Reset System

* Email-based reset link
* Secure password validation
* reCAPTCHA protection after multiple attempts

---

## 🛠️ Tech Stack

* **Backend:** Laravel (PHP)
* **Database:** MySQL
* **Auth:** Laravel Auth + Custom Security Layer
* **2FA:** Google2FA (PragmaRX)
* **Frontend:** Blade Templates + CSS
* **Security:** CSP, Argon2id, Encryption

---

## ⚙️ Installation

### 1️⃣ Clone Repository

git clone https://github.com/deepkarmakar-dev/vaultscribe.git
cd vaultscribe

### 2️⃣ Install Dependencies

composer install
npm install

### 3️⃣ Environment Setup

cp .env.example .env
php artisan key:generate

### 4️⃣ Configure .env

HASH_PEPPER=your_secret_pepper
NOCAPTCHA_SITEKEY=your_site_key
NOCAPTCHA_SECRET=your_secret_key
MAIL_MAILER=smtp

### 5️⃣ Run Migrations

php artisan migrate

### 6️⃣ Start Server

php artisan serve

---

## 🔐 Security Highlights

* Passwords are **double protected**:

  * Pepper (HMAC SHA256)
  * Argon2id hashing
* Notes are **encrypted at database level**
* OTP & 2FA secrets are securely handled
* Login & reset protected with CAPTCHA
* Security headers enforced via middleware

---

## 📂 Project Structure

Controllers/

* AuthController → Login system
* registerController → Registration + OTP
* PasswordController → Reset logic
* NoteController → Notes CRUD
* TwoFactorController → 2FA system

Models/

* User → Auth + 2FA
* Note → Encrypted notes
* ActivityLog → Logs

Middleware/

* SecurityHeaders → CSP & protection

---

## 🧪 Example Security Flow

1. User registers
2. OTP sent to email
3. User verifies account
4. Login with password
5. If enabled → 2FA required
6. Notes stored with encryption

---

## 📌 Important Notes

Weak passwords list:
storage/app/weak_passwords.txt

OTP expires in 5 minutes
After 3 failed attempts → CAPTCHA required
After 5 OTP attempts → session reset

---

## 🤝 Contribution

Pull requests are welcome!
If you want to improve security or features, feel free to contribute.

---


## 💡 Author

**Deep Karmakar**
Security-focused developer
