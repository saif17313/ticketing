# 🚌 BD Bus Tickets - Online Bus Ticketing System

A comprehensive bus ticketing system for Bangladesh with dual authentication (Passenger/Owner).

## 📋 Completed Features (Commits 1-5)

### ✅ Commit 1: Environment Setup
- MySQL database configuration for phpMyAdmin
- Laravel DomPDF for invoice generation
- Alpine.js for interactive components
- Tailwind CSS 4.0 for modern UI

### ✅ Commit 2: Core Database Tables
- **users**: Passenger and owner roles with phone, address, NID
- **districts**: All 64 Bangladesh districts with Bengali names
- **bus_companies**: Company management linked to owners
- **routes**: Source to destination with distance and duration

### ✅ Commit 3: Booking & Payment Tables
- **buses**: AC/Non-AC buses with seat layouts (2x2, 2x3, 2x1)
- **bus_schedules**: Daily trips with fare and seat availability
- **seats**: Individual seat management with 1-hour lock mechanism
- **bookings**: Reservation system with booking type and expiry
- **payments**: Multiple payment methods (bKash, Nagad, Rocket, card)

### ✅ Commit 4: Database Seeding
- 64 districts of Bangladesh populated
- 3 sample bus companies (Green Line, Hanif, Shohagh)
- 4 sample users (1 passenger + 3 owners)
- 12 popular routes including Dhaka to major cities

### ✅ Commit 5: Authentication System
- **Dual Login**: Toggle button for Passenger/Owner login
- **Registration**: Passengers only (owners added manually)
- **Middleware**: Role-based access control
- **Views**: Modern login/register forms with Tailwind CSS

## 🔑 Test Credentials

### Passenger Account
- Email: `ahmed@example.com`
- Password: `password`

### Owner Accounts
- **Green Line**: `kamal@greenline.com` / `password`
- **Hanif**: `rahim@hanif.com` / `password`
- **Shohagh**: `salim@shohagh.com` / `password`

## 🚀 How to Run

1. Start XAMPP (MySQL must be running)
2. Run: `php artisan serve`
3. Visit: `http://localhost:8000`
4. Click "Login" to test authentication

## 📊 Database Structure

View in phpMyAdmin: `http://localhost/phpmyadmin`
Database: `bus_ticketing_bd`

## 🎯 Next Features (Upcoming Commits)

- Commit 6: User registration with validation
- Commit 7: Owner manual login + middleware/guards
- Commit 8: Owner dashboard - Add/manage bus companies
- ...and 14 more commits!

## 🛠️ Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Blade + Tailwind CSS 4.0 + Alpine.js
- **Database**: MySQL (phpMyAdmin)
- **PDF**: Laravel DomPDF
- **Payment**: bKash, Nagad, Rocket integration (upcoming)
