# Tours and Travels, Hotels and Restaurants Booking System

Laravel Intern: **Kartik 12 | 82 B Group**

A complete booking management system for Tours, Travels, Hotels, and Restaurants built with Laravel, Tailwind CSS, and FilamentPHP.

---

## 🚀 Tech Stack

| Technology             | Description                                      |
| ---------------------- | ------------------------------------------------ |
| **Laravel Framework**  | Backend logic, authentication, API               |
| **Laravel Breeze**     | Authentication scaffolding with Blade / Tailwind |
| **MySQL Database**     | Relational database                              |
| **Tailwind CSS**       | Modern utility-first CSS framework               |
| **Vite**               | Lightning-fast bundler                           |
| **SweetAlert**         | Interactive alerts                               |
| **FilamentPHP v4**     | Admin panel builder                              |
| **FontAwesome Icons**  | Icons used in the frontend UI                    |

---

## 📌 System Features

### ✈️ Tours & Travels
- Add / Manage tour packages  
- Package booking system  
- Fare, destination & itinerary management  
- Availability checking  
- Customer details & confirmation system  

### 🏨 Hotels
- Room types (Single, Double, Deluxe, etc.)  
- Room booking system  
- Check-in / Check-out records  
- Room availability calendar  
- Price management based on room category  

### 🍽 Restaurants
- Table booking  
- Time-slot based reservation  
- Customer meal pre-order option (optional)  
- Status tracking (Booked / Reserved / Completed)  

### 🔐 Authentication & User Access
- Laravel Breeze login/registration  
- Admin / Staff roles  
- Protected admin dashboard  

### ⚙️ Admin Panel (Filament v4)
- Manage Tours  
- Manage Hotels  
- Manage Rooms  
- Manage Restaurants  
- Manage Bookings  
- Beautiful UI powered by Filament  

### 🎨 Additional Features
- Tailwind CSS responsive UI  
- SweetAlert confirmation modals  
- FontAwesome icons  
- Search, filters, pagination  
- Optimized for performance with Vite  

---

 ## 📥 Installation Guide

### 1️⃣ Clone Project

```bash

git clone https://github.com/ashish8216/Tours-and-Travels-Hotels-and-Restaurants-Booking-System.-.git
cd Tours-and-Travels-Hotels-and-Restaurants-Booking-System.-

```

## ⚙️ Project Setup

Follow these steps to install and run the project on your local machine.

### 1️⃣ Update Composer Dependencies

```bash

composer update

```

### 2️⃣ Copy Environment File

```bash

cp .env.example .env

```

### 3️⃣ Generate Application Key

```bash

php artisan key:generate

```

### 4️⃣ Run Fresh Migrations with Seeders

```bash

php artisan migrate:fresh --seed

```

### 5️⃣ Install Frontend Dependencies

```bash

npm install

```

### 6️⃣ Build Frontend Assets (Production)

```bash

npm run build

```

        
