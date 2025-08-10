# StageursApp - Laravel Version

A comprehensive PHP/Laravel web application for managing student internships and generating professional student badges.

## 🚀 Features

### Core Functionality
- **Student Management**: Complete CRUD operations for student records
- **Badge Generation**: Automatic PDF badge generation with student photos and information
- **Academic Structure Management**: Manage faculties, sections, and supervisors
- **Authentication & Authorization**: Secure admin login system with role-based access
- **File Management**: Photo upload and badge file management

### Student Management Features
- ✅ Add, edit, delete, and view student records
- ✅ Upload student photos (JPG, JPEG, PNG, GIF up to 1MB)
- ✅ National ID card number validation (8 digits)
- ✅ Associate students with supervisors and academic sections
- ✅ Track internship start and end dates
- ✅ Automatic badge generation upon student creation/update

### Badge System
- ✅ **Automatic PDF Generation**: Creates professional student badges
- ✅ **Photo Integration**: Includes student photos in badges
- ✅ **Complete Information**: Displays name, ID, contact info, supervisor, section, faculty
- ✅ **Multiple Formats**: Download, print, and preview badges
- ✅ **Batch Operations**: Generate badges for all students or missing badges only

### Academic Structure
- ✅ **Faculty Management**: Create and manage academic faculties
- ✅ **Section Management**: Organize sections within faculties
- ✅ **Supervisor Management**: Manage internship supervisors (encadreurs)
- ✅ **Hierarchical Organization**: Faculty → Section → Student relationships

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 12.0
- **Database**: MySQL/PostgreSQL with Eloquent ORM
- **Authentication**: Laravel's built-in authentication with custom admin guard
- **PDF Generation**: DomPDF
- **File Handling**: Laravel Storage with Intervention Image

### Frontend
- **UI Framework**: Bootstrap 5
- **JavaScript**: jQuery
- **Styling**: Custom CSS with responsive design
- **Icons**: Font Awesome

## 📁 Project Structure

```
stageurs-laravel/
├── app/
│   ├── Http/Controllers/     # Controllers
│   │   ├── HomeController.php
│   │   ├── AdminController.php
│   │   └── EtudiantController.php
│   ├── Models/               # Eloquent Models
│   │   ├── Admin.php
│   │   ├── Etudiant.php
│   │   ├── Encadreur.php
│   │   ├── Faculty.php
│   │   └── Section.php
│   └── Services/             # Business Logic
│       └── BadgeService.php
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   └── views/                # Blade templates
│       ├── layouts/
│       ├── home/
│       ├── admin/
│       └── etudiants/
└── routes/
    └── web.php               # Web routes
```

## 🗄️ Database Schema

### Core Entities
- **etudiants**: Student information with photo and badge paths
- **encadreurs**: Supervisor information (name, email, function, phone)
- **faculties**: Academic faculty/department
- **sections**: Academic section within a faculty
- **admins**: System administrators

### Relationships
- Faculty → Sections (One-to-Many)
- Section → Students (One-to-Many)
- Encadreur → Students (One-to-Many)
- Faculty → Section → Student (Hierarchical)

## 🔧 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- XAMPP (for local development)

### Step 1: Clone and Setup
```bash
# Navigate to the Laravel project directory
cd stageurs-laravel

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env
```

### Step 2: Configure Environment
Edit `.env` file with your database settings:

```env
APP_NAME="StageursApp"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stageurs_laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Database Setup
```bash
# Generate application key
php artisan key:generate

# Create database (if using MySQL)
mysql -u root -p
CREATE DATABASE stageurs_laravel;
exit

# Run migrations
php artisan migrate

# Seed the database with default admin user
php artisan db:seed
```

### Step 4: File Storage Setup
```bash
# Create storage link for public access to uploaded files
php artisan storage:link

# Create necessary directories
mkdir -p storage/app/public/photos
mkdir -p storage/app/public/badges
```

### Step 5: Start Development Server
```bash
# Start Laravel development server
php artisan serve
```

The application will be available at `http://localhost:8000`

## 🔐 Default Admin Account

- **Username**: admin
- **Password**: admin123

## 🚀 Usage Guide

### Admin Login
1. Navigate to `http://localhost:8000`
2. Click "Admin Login" or go to `/login`
3. Use admin credentials to access the system
4. Access the admin dashboard at `/admin`

### Managing Students
1. **Add Student**: Navigate to `/etudiants/create`
   - Fill in student information
   - Upload photo (optional)
   - Select supervisor and section
   - Badge is automatically generated

2. **View Students**: Navigate to `/etudiants`
   - See all students with their details
   - Access individual student details

3. **Edit Student**: Click edit on any student
   - Update information and photo
   - Badge is automatically regenerated

4. **Badge Operations**:
   - **Download**: Get PDF badge file
   - **Preview**: View badge in browser
   - **Regenerate**: Create new badge

### Managing Academic Structure
1. **Faculties**: `/admin/faculties` - Add/edit/delete faculties
2. **Sections**: `/admin/sections` - Manage sections within faculties
3. **Supervisors**: `/admin/encadreurs` - Manage internship supervisors

### Bulk Operations
- **Generate All Badges**: Admin dashboard option
- **Generate Missing Badges**: Admin dashboard option for students without badges

## 🔒 Security Features

- **Password Hashing**: Laravel's built-in password hashing
- **Authentication**: Session-based authentication with admin guard
- **Authorization**: Middleware protection for admin routes
- **File Validation**: File type and size validation for uploads
- **CSRF Protection**: Laravel's built-in CSRF protection

## 📄 Badge Features

### Badge Content
- Student photo (if available)
- Full name and national ID
- Contact information (email, phone)
- Academic information (section, faculty)
- Supervisor information
- Internship dates
- Generation timestamp

### Badge Operations
- **Automatic Generation**: Created when student is added/updated
- **PDF Format**: Professional A4 layout
- **File Management**: Automatic cleanup of old badges
- **Error Handling**: Retry logic for file operations

## 🎨 UI/UX Features

- **Responsive Design**: Works on desktop and mobile devices
- **Bootstrap 5**: Modern, clean interface
- **Intuitive Navigation**: Clear menu structure
- **Form Validation**: Client and server-side validation
- **Success/Error Messages**: User-friendly feedback
- **Photo Upload**: Drag-and-drop style interface

## 🐛 Troubleshooting

### Common Issues
1. **Database Connection**: Ensure MySQL/PostgreSQL is running and credentials are correct
2. **File Permissions**: Ensure `storage/app/public` directory is writable
3. **Badge Generation**: Check if DomPDF is properly installed
4. **Photo Upload**: Verify file size and type restrictions

### Logs
- Application logs are in `storage/logs/laravel.log`
- Check console output for detailed error messages

## 🔧 Configuration

### Database Configuration
Update `.env` file with your database settings.

### File Storage
The application uses Laravel's public disk for file storage:
- Photos: `storage/app/public/photos/`
- Badges: `storage/app/public/badges/`

## 🚀 Deployment

### Production Deployment
1. **Environment Setup**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize for Production**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Web Server Configuration**
   - Configure your web server (Apache/Nginx) to point to the `public` directory
   - Ensure proper file permissions
   - Set up SSL certificates

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License.

## 📞 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

---

**StageursApp** - Professional Student Internship Management System (Laravel Version)
