# Music Store API - Setup Guide

This guide will walk you through setting up and running the Music Store API project.

## Prerequisites

Before starting, ensure you have installed:

- **PHP 8.2+** (Check: `php -v`)
- **Composer** (Check: `composer -v`)
- **MySQL 8.0+** (Check: `mysql -V`)
- **Node.js 18+** (Optional, for frontend assets)

## Installation Steps

### 1. Clone/Navigate to Project

```bash
cd h:\Larave_projects\music-store
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Create Environment File

```bash
# Copy the example environment file
cp .env.example .env
# Or on Windows:
copy .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Configure Database

Edit `.env` file and update these values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=music_store
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Note**: Create the database first:

```sql
CREATE DATABASE music_store;
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

This creates all required tables:

- users
- artists
- albums
- songs
- oauth_personal_access_clients (Passport)

### 7. Install Passport (OAuth2 Authentication)

```bash
php artisan passport:install
```

This generates the encryption keys needed for API authentication.

### 8. Create Sample Data (Optional)

```bash
php artisan db:seed
```

Or if you have specific seeders:

```bash
php artisan db:seed --class=UserSeeder
```

### 9. Link Storage (For File Uploads)

```bash
php artisan storage:link
```

This creates a symbolic link from `storage/app/public` to `public/storage` for file access.

## Running the Project

### Start the Development Server

```bash
php artisan serve
```

The API will be available at:

- **API Base URL**: `http://localhost:8000/api`
- **Swagger UI Docs**: `http://localhost:8000/api/documentation`

### In a Separate Terminal - Run Queue (Optional)

If you have async jobs:

```bash
php artisan queue:work
```

## Project Structure

```
music-store/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/      # API Controllers
│   │   ├── Requests/Api/         # Form Requests (Validation)
│   │   └── Resources/            # API Response Resources
│   ├── Models/                   # Eloquent Models
│   ├── Services/                 # Business Logic
│   ├── Repositories/             # Data Access Layer
│   └── Core/                     # ApiResponse, Result classes
├── routes/
│   ├── api.php                   # API Routes
│   └── web.php                   # Web Routes
├── database/
│   ├── migrations/               # Database Migrations
│   ├── seeders/                  # Database Seeders
│   └── factories/                # Model Factories
├── docs/
│   └── openapi/                  # Swagger/OpenAPI Documentation
├── public/

└── config/
    ├── auth.php                  # Authentication Config (Passport)
    └── database.php              # Database Config
```

## Testing the API

### 1. View API Documentation

Open in browser:

```
http://localhost:8000/api/documentation
```

This uses **l5-swagger** package which automatically generates interactive Swagger/OpenAPI documentation.

### 2. Register a User

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 3. Login to Get Token

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

Save the `access_token` from the response.

### 4. Create Artist (Protected)

```bash
curl -X POST http://localhost:8000/api/artists \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: multipart/form-data" \
  -F "name=The Beatles" \
  -F "bio=A legendary band from Liverpool" \
  -F "avatar=@path/to/image.jpg"
```

### 5. List Artists

```bash
curl -X GET "http://localhost:8000/api/artists?page=1&limit=15"
```

## Available Endpoints

### Authentication (Public)

- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user

### Artists (Protected)

- `GET /api/artists` - List artists (paginated)
- `POST /api/artists` - Create artist (requires auth + file upload)
- `GET /api/artists/{id}` - Get artist with songs and albums

### Songs (Protected)

- `GET /api/songs` - List songs (paginated)
- `POST /api/songs` - Create song (requires auth)
- `GET /api/songs/{id}` - Get song details

### Albums (Protected)

- `POST /api/albums` - Create album (requires auth)

## Common Issues & Solutions

### Issue: "Class 'Passport' not found"

**Solution**: Run migrations first

```bash
php artisan migrate
php artisan passport:install
```

### Issue: "SQLSTATE[HY000]: General error: 1030 Got error"

**Solution**: Check database connection in `.env`

```bash
php artisan tinker
DB::select('select 1')
```

### Issue: "Port 8000 already in use"

**Solution**: Use a different port

```bash
php artisan serve --port=8001
```

### Issue: "File not found" for avatar uploads

**Solution**: Create storage link

```bash
php artisan storage:link
```

### Issue: CORS errors

**Solution**: The API is configured to accept cross-origin requests. Check `config/cors.php` if issues persist.

## Environment Variables

Key environment variables in `.env`:

```env
APP_NAME=MusicStore
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=music_store
DB_USERNAME=root
DB_PASSWORD=

# Mail (Optional)
MAIL_DRIVER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## Database Migrations

All migrations are in `database/migrations/`:

1. **Users Table** - User authentication
2. **Artists Table** - Artist information
3. **Albums Table** - Album records
4. **Songs Table** - Song records
5. **Oauth Tables** - Passport authentication

Run migrations:

```bash
php artisan migrate
```

Rollback migrations:

```bash
php artisan migrate:rollback
```

Reset all migrations:

```bash
php artisan migrate:refresh
```

## API Features

✅ **Passport OAuth2** - Secure API authentication
✅ **Layered Architecture** - Controllers → Services → Repositories
✅ **Validation** - Form Requests with custom rules
✅ **Error Handling** - Result pattern with consistent responses
✅ **Pagination** - Built-in pagination support
✅ **File Uploads** - Avatar upload for artists
✅ **API Resources** - Consistent response formatting
✅ **Strict Types** - Type-safe PHP code
✅ **l5-swagger Documentation** - Interactive API docs with Swagger UI

## Quick Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName -m

# Create new controller
php artisan make:controller Api/ControllerName

# Run tests
php artisan test

# Check code style
php artisan pint

# Tinker (Interactive shell)
php artisan tinker
```

## Next Steps

1. ✅ Complete the setup above
2. ✅ Visit `http://localhost:8000/api/documentation` to see API docs
3. ✅ Register and login to get authentication token
4. ✅ Test creating artists, songs, and albums
5. ✅ Integrate with your frontend application

## Troubleshooting

If you encounter issues:

1. **Clear all caches**:

    ```bash
    php artisan optimize:clear
    ```

2. **Check logs**:

    ```bash
    tail -f storage/logs/laravel.log
    ```

3. **Verify database connection**:

    ```bash
    php artisan db:seed --env=testing
    ```

4. **Run migrations fresh**:
    ```bash
    php artisan migrate:refresh --seed
    ```

For more help, check the `docs/` directory for additional documentation.
