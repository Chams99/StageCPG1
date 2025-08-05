# StageursApp - Student Internship Management System

## Overview

StageursApp is a comprehensive web application built with ASP.NET Core MVC for managing student internships and generating professional student badges. The application provides a complete solution for educational institutions to track student information, manage supervisors (encadreurs), organize academic structures, and automatically generate printable student identification badges.

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
- ✅ **HTML Preview**: Web-based badge preview before generation

### Academic Structure
- ✅ **Faculty Management**: Create and manage academic faculties
- ✅ **Section Management**: Organize sections within faculties
- ✅ **Supervisor Management**: Manage internship supervisors (encadreurs)
- ✅ **Hierarchical Organization**: Faculty → Section → Student relationships

### Admin Features
- ✅ **Dashboard**: Overview of all system data
- ✅ **User Management**: Secure admin authentication
- ✅ **Bulk Operations**: Generate badges for multiple students
- ✅ **Data Management**: Full CRUD operations for all entities

## 🛠️ Technology Stack

### Backend
- **Framework**: ASP.NET Core 9.0 MVC
- **Database**: PostgreSQL with Entity Framework Core
- **Authentication**: Cookie-based authentication with SHA256 password hashing
- **PDF Generation**: iTextSharp.LGPLv2.Core
- **Environment Management**: DotNetEnv

### Frontend
- **UI Framework**: Bootstrap 5
- **JavaScript**: jQuery with validation
- **Styling**: Custom CSS with responsive design
- **Icons**: Font Awesome

### Development Tools
- **Package Manager**: NuGet
- **Database Migrations**: Entity Framework Core Migrations
- **Development Environment**: .NET 9.0

## 📁 Project Structure

```
StageursApp/
├── Controllers/           # MVC Controllers
│   ├── AdminController.cs    # Admin dashboard and management
│   ├── EtudiantController.cs # Student management
│   └── HomeController.cs     # Authentication and home
├── Models/               # Data models
│   ├── Admin.cs             # Admin user model
│   ├── Etudiant.cs          # Student model
│   ├── Encadreur.cs         # Supervisor model
│   ├── Faculty.cs           # Faculty model
│   ├── Section.cs           # Section model
│   └── ViewModels/          # View models
├── Services/             # Business logic services
│   ├── AuthService.cs       # Authentication service
│   └── BadgeService.cs      # PDF badge generation
├── Data/                 # Data access layer
│   ├── StageursContext.cs   # Entity Framework context
│   └── StageursContextFactory.cs
├── Views/                # Razor views
│   ├── Admin/               # Admin interface views
│   ├── Etudiant/            # Student management views
│   └── Home/                # Authentication views
├── wwwroot/              # Static files
│   ├── uploads/             # File uploads (photos, badges)
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
└── Migrations/           # Database migrations
```

## 🗄️ Database Schema

### Core Entities
- **Etudiant**: Student information with photo and badge paths
- **Encadreur**: Supervisor information (name, email, function, phone)
- **Faculty**: Academic faculty/department
- **Section**: Academic section within a faculty
- **Admin**: System administrators

### Relationships
- Faculty → Sections (One-to-Many)
- Section → Students (One-to-Many)
- Encadreur → Students (One-to-Many)
- Faculty → Section → Student (Hierarchical)

## 🔧 Installation & Setup

### Prerequisites
- .NET 9.0 SDK
- PostgreSQL 12+ 
- Git

### Environment Setup
1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd StageursApp
   ```

2. **Configure environment variables**
   Create a `.env` file in the project root:
   ```env
   DB_PASSWORD=your_postgres_password
   ADMIN_PASSWORD=your_admin_password
   ```

3. **Database setup**
   ```bash
   # Update connection string in appsettings.json
   # Run migrations
   dotnet ef database update
   ```

4. **Install dependencies**
   ```bash
   dotnet restore
   ```

5. **Run the application**
   ```bash
   dotnet run
   ```

### Default Admin Account
- **Username**: admin
- **Password**: Set via `ADMIN_PASSWORD` environment variable (defaults to "admin123")

## 🚀 Usage Guide

### Admin Login
1. Navigate to `/Home/Login`
2. Use admin credentials to access the system
3. Access the admin dashboard at `/Admin`

### Managing Students
1. **Add Student**: Navigate to `/Etudiant/Create`
   - Fill in student information
   - Upload photo (optional)
   - Select supervisor and section
   - Badge is automatically generated

2. **View Students**: Navigate to `/Etudiant/Index`
   - See all students with their details
   - Access individual student details

3. **Edit Student**: Click edit on any student
   - Update information and photo
   - Badge is automatically regenerated

4. **Badge Operations**:
   - **Download**: Get PDF badge file
   - **Print**: Print badge directly
   - **Preview**: View HTML preview
   - **Regenerate**: Create new badge

### Managing Academic Structure
1. **Faculties**: `/Admin/Faculties` - Add/edit/delete faculties
2. **Sections**: `/Admin/Sections` - Manage sections within faculties
3. **Supervisors**: `/Admin/Encadreurs` - Manage internship supervisors

### Bulk Operations
- **Generate All Badges**: `/Admin/GenerateAllBadges` - Create badges for all students
- **Generate Missing Badges**: Admin dashboard option for students without badges

## 🔒 Security Features

- **Password Hashing**: SHA256 encryption for admin passwords
- **Authentication**: Cookie-based authentication with 8-hour expiration
- **Authorization**: Role-based access control (Admin role)
- **File Validation**: File type and size validation for uploads
- **CSRF Protection**: Anti-forgery tokens on forms

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
- **Preview System**: HTML preview before generation

## 🎨 UI/UX Features

- **Responsive Design**: Works on desktop and mobile devices
- **Bootstrap 5**: Modern, clean interface
- **Intuitive Navigation**: Clear menu structure
- **Form Validation**: Client and server-side validation
- **Success/Error Messages**: User-friendly feedback
- **Photo Upload**: Drag-and-drop style interface

## 🔧 Configuration

### Database Configuration
Update `appsettings.json`:
```json
{
  "ConnectionStrings": {
    "DefaultConnection": "Host=localhost;Port=5432;Database=stageurs_db;Username=postgres;Password=${DB_PASSWORD}"
  }
}
```

### Environment Variables
- `DB_PASSWORD`: PostgreSQL database password
- `ADMIN_PASSWORD`: Initial admin password

## 🚀 Deployment

### Production Deployment
1. **Build the application**
   ```bash
   dotnet publish -c Release
   ```

2. **Configure production settings**
   - Update `appsettings.Production.json`
   - Set proper connection strings
   - Configure logging

3. **Deploy to web server**
   - Copy published files to web server
   - Configure IIS or other web server
   - Set up SSL certificates

### Docker Deployment (Optional)
```dockerfile
FROM mcr.microsoft.com/dotnet/aspnet:9.0
COPY bin/Release/net9.0/publish/ App/
WORKDIR /App
ENTRYPOINT ["dotnet", "StageursApp.dll"]
```

## 🐛 Troubleshooting

### Common Issues
1. **Database Connection**: Ensure PostgreSQL is running and credentials are correct
2. **File Permissions**: Ensure `wwwroot/uploads` directory is writable
3. **Badge Generation**: Check if iTextSharp is properly installed
4. **Photo Upload**: Verify file size and type restrictions

### Logs
- Application logs are configured in `appsettings.json`
- Check console output for detailed error messages

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

---

**StageursApp** - Professional Student Internship Management System 