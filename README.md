# Vaultscribe Secure Notes

Vaultscribe is a secure notes web application built with Laravel.
It allows users to safely store and manage personal notes with authentication and privacy-focused design.

## Features

* User authentication
* Secure note storage
* Privacy-focused architecture
* Laravel backend
* Livewire + Blade frontend

## Tech Stack

* Laravel
* Livewire
* Blade
* Tailwind CSS
* MySQL / SQLite

## Installation

```bash
git clone https://github.com/deepkarmakar-dev/vaultscribe-secure-notes.git
cd vaultscribe-secure-notes
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

## License

This project is open-sourced under the MIT License.
