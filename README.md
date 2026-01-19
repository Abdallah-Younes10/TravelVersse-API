# 🌍 TravelVerse API

TravelVerse API is a robust, scalable RESTful backend built with **Laravel**, designed to power a complete travel and tourism platform. It provides secure, role-based APIs for managing trips, hotels, flights, activities, cruises, cars, restaurants, reservations, reviews, and user authentication.

---

## 🚀 Features Overview

* RESTful API architecture
* Token-based authentication using **Laravel Sanctum**
* Role-based authorization (Admin, Tour Guide, User)
* MySQL database
* CORS-enabled (ready for React / Mobile apps)
* Modular & clean Laravel structure
* Password reset & account management

---

## 🛠 Tech Stack

* **Backend:** Laravel
* **Authentication:** Laravel Sanctum (Bearer Token)
* **Database:** MySQL
* **API Style:** REST
* **Deployment:** Railway / VPS / Shared Hosting

---

## 🔐 Authentication Flow

### Register

```
POST /api/register
```

### Login

```
POST /api/login
```

Response:

```json
{
  "token": "1|xxxxxxxxxxxxxxxx"
}
```

### Authenticated Requests

```
Authorization: Bearer {token}
```

---

## 👥 User Roles

| Role       | Permissions                       |
| ---------- | --------------------------------- |
| User       | Browse, book, review, favorite    |
| Tour Guide | Manage trips, activities, flights |
| Admin      | Full system access                |

---

## 📦 API Modules

### 🧳 Trips

* GET /api/trips
* GET /api/trips/{id}
* POST /api/trips (Admin / Guide)
* PUT /api/trips/{id}
* DELETE /api/trips/{id}

---

### 🏨 Hotels

* GET /api/hotels
* GET /api/hotels/{id}
* POST /api/hotels (Admin)
* PUT /api/hotels/{id}
* DELETE /api/hotels/{id}

---

### ✈️ Flights

* GET /api/flights
* GET /api/flights/{id}
* POST /api/flights (Admin / Guide)
* PUT /api/flights/{id}
* DELETE /api/flights/{id}

---

### 🍽 Restaurants

* GET /api/restaurants
* GET /api/restaurants/{id}
* POST /api/restaurants (Admin)
* PUT /api/restaurants/{id}
* DELETE /api/restaurants/{id}

---

### 🚗 Cars

* GET /api/cars
* GET /api/cars/{id}
* POST /api/cars (Admin)
* PUT /api/cars/{id}
* DELETE /api/cars/{id}

---

### 🚢 Cruises

* GET /api/cruises
* GET /api/cruises/{id}
* POST /api/cruises (Admin)
* PUT /api/cruises/{id}
* DELETE /api/cruises/{id}

---

### 🎯 Activities

* GET /api/activities
* GET /api/activities/{id}
* GET /api/activities/types
* POST /api/activities (Admin / Guide)
* PUT /api/activities/{id}
* DELETE /api/activities/{id}

---

### ⭐ Reviews

* GET /api/reviews
* GET /api/reviews/{id}
* POST /api/reviews (User)
* DELETE /api/reviews/{id} (Admin)

---

### ❤️ Favorites

* GET /api/favorites
* POST /api/favorites
* DELETE /api/favorites/{id}

---

### 📦 Reservations

* GET /api/reservations
* POST /api/reservations
* GET /api/reservations/{id}
* PATCH /api/reservations/{id}/status
* POST /api/reservations/{id}/pay
* DELETE /api/reservations/{id}

---

## 🔒 Middleware & Security

* auth:sanctum
* guest.only
* usertype:user
* usertype:admin,tour_guide
* Rate limiting (throttle)

---

## 🌐 CORS Configuration

* Enabled using Fruitcake CORS Middleware
* Supports external frontends (React, Mobile)
* Configurable allowed origins for production

---

## ⚙️ Installation & Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## 🚀 Deployment

* Ready for Railway
* Supports MySQL plugins
* Environment-based configuration
* HTTPS recommended

---

## 📌 Use Cases

* React / Next.js Frontend
* Mobile Applications
* Admin Dashboard
* Tourism Management Systems

---

## 📄 License

This project is open-source and available for educational and commercial use.

---

## ✨ Author 
Abdallah Younes

Developed with ❤️ using Laravel
