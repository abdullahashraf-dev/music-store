@echo off
echo ========================================
echo  Laravel 11 Music Store Docker Setup
echo ========================================
echo.

REM Check if Docker Desktop is running
echo Checking Docker Desktop...
docker version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Docker Desktop is not running!
    echo Please start Docker Desktop and try again.
    pause
    exit /b 1
)
echo Docker Desktop is running ✓
echo.

REM Check if src directory is empty
if exist "src\composer.json" (
    echo Laravel already installed in src directory ✓
    goto :env_setup
)

REM Install Laravel 11
echo Installing Laravel 11...
docker run --rm -v %cd%\src:/app composer create-project laravel/laravel /app --prefer-dist
if errorlevel 1 (
    echo ERROR: Failed to install Laravel
    pause
    exit /b 1
)
echo Laravel 11 installed successfully ✓
echo.

:env_setup
REM Setup environment
echo Setting up environment...
if not exist ".env" (
    copy .env.example .env >nul
    echo Created .env file ✓
)

REM Update .env with Docker settings
powershell -Command "(Get-Content src\.env) -replace 'DB_HOST=127.0.0.1', 'DB_HOST=db' | Set-Content src\.env"
powershell -Command "(Get-Content src\.env) -replace 'DB_DATABASE=laravel', 'DB_DATABASE=music_store' | Set-Content src\.env"
powershell -Command "(Get-Content src\.env) -replace 'DB_PASSWORD=', 'DB_PASSWORD=' | Set-Content src\.env"
echo Environment configured ✓
echo.

REM Start Docker containers
echo Starting Docker containers...
docker-compose up -d
echo.

REM Wait for services to start
echo Waiting for services to start...
timeout /t 10 /nobreak >nul
echo.

REM Generate application key
echo Generating application key...
docker-compose exec -T app php artisan key:generate
echo Application key generated ✓
echo.

REM Set permissions
echo Setting file permissions...
docker-compose exec -T app chmod -R 775 storage bootstrap/cache
echo Permissions set ✓
echo.

REM Run migrations
echo Running database migrations...
docker-compose exec -T app php artisan migrate --force
echo Database migrated ✓
echo.

echo ========================================
echo  SETUP COMPLETE!
echo ========================================
echo.
echo Access URLs:
echo   Main Application:  http://localhost:8000
echo   phpMyAdmin:       http://localhost:8080
echo.
echo Database Credentials:
echo   Host: db
echo   Database: music_store
echo   Username: root
echo   Password: secret
echo.
echo Press any key to continue...
pause >nul