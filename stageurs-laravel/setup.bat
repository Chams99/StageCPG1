@echo off
echo ========================================
echo StageursApp Laravel Setup Script
echo ========================================
echo.

echo Checking PHP installation...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP is not installed or not in PATH
    echo Please install XAMPP and ensure PHP is in your PATH
    pause
    exit /b 1
)

echo Checking Composer installation...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Composer is not installed
    echo Please install Composer from https://getcomposer.org/
    pause
    exit /b 1
)

echo.
echo Installing PHP dependencies...
composer install --ignore-platform-req=ext-fileinfo

echo.
echo Copying environment file...
if not exist .env (
    copy .env.example .env
    echo Environment file created.
) else (
    echo Environment file already exists.
)

echo.
echo Generating application key...
php artisan key:generate

echo.
echo ========================================
echo Database Setup Instructions:
echo ========================================
echo 1. Start XAMPP Control Panel
echo 2. Start Apache and MySQL services
echo 3. Open phpMyAdmin (http://localhost/phpmyadmin)
echo 4. Create a new database named 'stageurs_laravel'
echo 5. Update .env file with your database credentials
echo 6. Run the following commands:
echo    php artisan migrate
echo    php artisan db:seed
echo    php artisan storage:link
echo.
echo Default admin credentials:
echo Username: admin
echo Password: admin123
echo.
echo After setup, start the server with:
echo php artisan serve
echo.
echo The application will be available at:
echo http://localhost:8000
echo ========================================
pause
