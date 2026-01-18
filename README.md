# 🎵 Music Store API

A robust RESTful API built with Laravel 11 for managing a digital music store. This application provides functionality for managing artists, albums, and songs with secure user authentication.

## 🚀 Features

- **Authentication**: Secure JWT-based authentication using Laravel Passport.
- **Role-based Access**: Public access for viewing, protected routes for management.
- **RESTful Endpoints**: Full CRUD operations for Artists, Albums, and Songs.
- **Standardized Responses**: Unified API response structure for success and errors.
- **Documentation**: Interactive API documentation via Swagger/OpenAPI.
- **Dockerized**: specific docker-compose setup for easy development.

## 🛠 Tech Stack

- **Framework**: Laravel 11
- **Database**: mysql 8.0
- **Authentication**: Laravel Passport
- **Documentation**: L5-Swagger (OpenAPI 3.0)
- **Containerization**: Docker & Docker Compose

## 📋 Prerequisites

Ensure you have the following installed:
- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- [Git](https://git-scm.com/)

---

## 🏗 Setup & Installation

### 1. Clone the Repository
```bash
git clone https://github.com/abdullahashraf-dev/music-store.git
cd music-store
```

### 2. Configure Environment
Copy the example environment file:
```bash
cp .env.docker .env
```

### 3. Start Docker Containers
Build and start the services:
```bash
docker-compose up -d --build
```

### 4. Install Dependencies
Install PHP dependencies within the container:
```bash
docker exec music-store-app composer install
```

### 5. Setup Database
Run migrations and seed the database with sample data:
```bash
docker exec music-store-app php artisan migrate:fresh --seed
```

### 6. Configure Passport
Generate encryption keys and create the personal access client:
```bash
docker exec music-store-app php artisan passport:keys --force
docker exec music-store-app php artisan passport:client --personal --name="Music Store Personal Access Client"
```
> **Note**: If asked during the client creation, verify that the `users` provider is selected.

---

## 📚 API Documentation

The API is fully documented using Swagger/OpenAPI. Once the application is running, you can access the interactive documentation at:

👉 **[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)**

### Key Endpoints

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :---: |
| POST | `/api/auth/register` | Register a new user | ❌ |
| POST | `/api/auth/login` | Login and get API token | ❌ |
| GET | `/api/artists` | List all artists | ❌ |
| POST | `/api/artists` | Create a new artist | ✅ |
| GET | `/api/albums` | List all albums | ❌ |
| POST | `/api/albums` | Create a new album | ✅ |
| GET | `/api/songs` | List all songs | ❌ |
| POST | `/api/songs` | Add a new song | ✅ |

To run the test suite:
```bash
docker exec music-store-app php artisan test
```

## 📂 Project Structure

- `app/Http/Controllers/Api` - API Controllers
- `app/Services` - Business Logic Layer
- `app/Repositories` - Data Access Layer
- `app/Core` - Core utilities (Response helpers)
- `database/migrations` - Database Structure
- `routes/api.php` - API Routes Definition

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
