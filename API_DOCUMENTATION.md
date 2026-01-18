# Music Store API Documentation

## Overview

This is a comprehensive Music Store API built with Laravel using Passport authentication, layered architecture with controllers, services, repositories, and form requests for validation.

## Architecture

### Layered Architecture

- **Controllers** (`app/Http/Controllers/Api/*`) - Handles HTTP requests/responses
- **Services** (`app/Services/*`) - Contains business logic
- **Repositories** (`app/Repositories/*`) - Data access layer
- **Requests** (`app/Http/Requests/Api/*`) - Form request validation
- **Resources** (`app/Http/Resources/*`) - API response transformation
- **Core** (`app/Core/*`) - Result pattern for error handling

### Tech Stack

- **Framework**: Laravel 11
- **Authentication**: Laravel Passport (OAuth2)
- **Validation**: Form Requests
- **API Responses**: Laravel Resources
- **Error Handling**: Result Pattern

## Installation & Setup

### 1. Database Setup

```bash
# Configure .env with your database credentials
php artisan migrate
```

### 2. Passport Setup

```bash
# Install Passport keys
php artisan passport:install

# Create personal access client (optional)
php artisan passport:client --personal
```

### 3. Run Application

```bash
# Using Laravel Sail (Docker)
./vendor/bin/sail up

# Or using built-in server
php artisan serve
```

## API Endpoints

### Authentication

All authentication endpoints don't require API token.

#### Register User

```
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response (201):
{
  "success": true,
  "status_code": 201,
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "created_at": "2026-01-17T21:30:00Z",
    "updated_at": "2026-01-17T21:30:00Z"
  }
}
```

#### Login User

```
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}

Response (200):
{
  "success": true,
  "status_code": 200,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "created_at": "2026-01-17T21:30:00Z",
      "updated_at": "2026-01-17T21:30:00Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

### Artists

#### Get All Artists

```
GET /api/artists?page=1&limit=15
Content-Type: application/json

Response (200):
{
  "success": true,
  "status_code": 200,
  "data": [
    {
      "id": 1,
      "name": "Artist Name",
      "bio": "Artist bio",
      "avatar": "path/to/avatar.jpg",
      "created_at": "2026-01-17T21:30:00Z",
      "updated_at": "2026-01-17T21:30:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}
```

#### Get Single Artist with Songs and Albums

```
GET /api/artists/{id}
Content-Type: application/json

Response (200):
{
  "success": true,
  "status_code": 200,
  "data": {
    "id": 1,
    "name": "Artist Name",
    "bio": "Artist bio",
    "avatar": "path/to/avatar.jpg",
    "songs": [
      {
        "id": 1,
        "title": "Song Title",
        "duration": 180,
        "album": null,
        "artists": [],
        "created_at": "2026-01-17T21:30:00Z",
        "updated_at": "2026-01-17T21:30:00Z"
      }
    ],
    "albums": [
      {
        "id": 1,
        "title": "Album Title",
        "artwork_url": "url",
        "created_at": "2026-01-17T21:30:00Z",
        "updated_at": "2026-01-17T21:30:00Z"
      }
    ],
    "created_at": "2026-01-17T21:30:00Z",
    "updated_at": "2026-01-17T21:30:00Z"
  }
}
```

#### Create Artist (Authenticated)

```
POST /api/artists
Authorization: Bearer {access_token}
Content-Type: multipart/form-data

{
  "name": "New Artist",
  "bio": "Artist biography",
  "avatar": <file>
}

Response (201):
{
  "success": true,
  "status_code": 201,
  "data": {
    "id": 1,
    "name": "New Artist",
    "bio": "Artist biography",
    "avatar": "avatars/filename.jpg",
    "created_at": "2026-01-17T21:30:00Z",
    "updated_at": "2026-01-17T21:30:00Z"
  }
}
```

### Albums

#### Create Album (Authenticated)

```
POST /api/albums
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "title": "Album Title",
  "artwork_url": "https://example.com/image.jpg"
}

Response (201):
{
  "success": true,
  "status_code": 201,
  "data": {
    "id": 1,
    "title": "Album Title",
    "artwork_url": "https://example.com/image.jpg",
    "created_at": "2026-01-17T21:30:00Z",
    "updated_at": "2026-01-17T21:30:00Z"
  }
}
```

### Songs

#### Get All Songs

```
GET /api/songs?page=1&limit=15
Content-Type: application/json

Response (200):
{
  "success": true,
  "status_code": 200,
  "data": [
    {
      "id": 1,
      "title": "Song Title",
      "duration": 180,
      "album": {
        "id": 1,
        "title": "Album Title",
        "artwork_url": "url",
        "created_at": "2026-01-17T21:30:00Z",
        "updated_at": "2026-01-17T21:30:00Z"
      },
      "artists": [
        {
          "id": 1,
          "name": "Artist Name",
          "bio": "bio",
          "avatar": "avatar.jpg",
          "created_at": "2026-01-17T21:30:00Z",
          "updated_at": "2026-01-17T21:30:00Z"
        }
      ],
      "created_at": "2026-01-17T21:30:00Z",
      "updated_at": "2026-01-17T21:30:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}
```

#### Get Single Song

```
GET /api/songs/{id}
Content-Type: application/json

Response (200):
{
  "success": true,
  "status_code": 200,
  "data": {
    "id": 1,
    "title": "Song Title",
    "duration": 180,
    "album": { ... },
    "artists": [ ... ],
    "created_at": "2026-01-17T21:30:00Z",
    "updated_at": "2026-01-17T21:30:00Z"
  }
}
```

#### Create Song (Authenticated)

```
POST /api/songs
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "title": "Song Title",
  "duration": 180,
  "artist_id": 1,
  "album_id": 1
}

Response (201):
{
  "success": true,
  "status_code": 201,
  "data": {
    "id": 1,
    "title": "Song Title",
    "duration": 180,
    "album": { ... },
    "artists": [ ... ],
    "created_at": "2026-01-17T21:30:00Z",
    "updated_at": "2026-01-17T21:30:00Z"
  }
}
```

## Error Handling

All errors follow the Result Pattern and return consistent responses:

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Error details"]
    }
}
```

### Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Validation Rules

### Register

- `name` - Required, string, max 255
- `username` - Required, string, max 255, unique
- `email` - Required, email, unique
- `password` - Required, min 8, confirmed

### Login

- `email` - Required, email
- `password` - Required, string

### Create Album

- `title` - Required, string, max 255
- `artwork_url` - Required, valid URL

### Create Song

- `title` - Required, string, max 255
- `duration` - Required, integer, min 1
- `artist_id` - Required, exists in artists table
- `album_id` - Optional, exists in albums table

### Create Artist

- `name` - Required, string, max 255
- `bio` - Optional, string
- `avatar` - Optional, image file (jpeg, png, jpg, gif), max 2MB

## Authentication

Use the access token received from login in all protected endpoints:

```
Authorization: Bearer {access_token}
```

Protected endpoints require this header to be present.

## Response Format

All successful responses follow this format:

```json
{
  "success": true,
  "status_code": 200,
  "data": { ... }
}
```

Paginated responses include pagination info:

```json
{
  "success": true,
  "status_code": 200,
  "data": [ ... ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}
```
