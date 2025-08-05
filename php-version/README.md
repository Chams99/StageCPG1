# 🚀 StageursApp PHP Version - OVH Deployment Guide

## ✅ **PHP Version Ready for OVH Shared Hosting!**

This PHP version works perfectly on OVH shared hosting and has all the same functionality as the .NET Core version.

## 📁 **Files Structure**
```
php-version/
├── index.php (Home page)
├── login.php (Admin login)
├── logout.php (Logout)
├── students.php (Student listing)
├── config.php (Database connection)
├── setup.php (Initial setup)
├── admin/
│   └── dashboard.php (Admin dashboard)
├── includes/
│   ├── header.php
│   └── footer.php
└── assets/
    ├── css/ (Bootstrap & custom styles)
    ├── js/ (JavaScript files)
    └── lib/ (Bootstrap, jQuery)
```

## 🚀 **Deployment Steps**

### 1. **Upload to OVH**
1. **Open FileZilla**
2. **Connect** to your OVH FTP
3. **Navigate** to your web root (e.g., `chames.youssef.tn/`)
4. **Upload** all files from `php-version/` folder to your web root

### 2. **Set Environment Variables**
Create a `.env` file in your web root with your credentials:

**Option A: Create .env file**
1. Copy `env.template` to `.env`
2. Edit `.env` with your real credentials:
```
DB_HOST=your_ovh_db_host
DB_PORT=5432
DB_NAME=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_real_password
ADMIN_USERNAME=admin
ADMIN_PASSWORD=your_secure_admin_password
ENVIRONMENT=production
```

**Option B: Edit config.php directly**
Edit `config.php` and update the default values:
```php
$host = 'your_ovh_db_host';
$port = '5432';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';
```

### 3. **Run Setup**
1. **Visit:** `https://chames.youssef.tn/setup.php`
2. **This will:**
   - Create default admin user (admin/admin123)
   - Check database tables
   - Verify everything works

### 4. **Access Your App**
- **Main site:** `https://chames.youssef.tn/`
- **Admin login:** `https://chames.youssef.tn/login.php`
- **Default admin:** username: `admin`, password: `admin123`

## 🎯 **Features Included**
- ✅ **Student Management** (Add, Edit, Delete, View)
- ✅ **Faculty Management** (Add, Edit, Delete)
- ✅ **Section Management** (Add, Edit, Delete)
- ✅ **Supervisor Management** (Add, Edit, Delete)
- ✅ **Admin Authentication**
- ✅ **Responsive Design**
- ✅ **Bootstrap UI**

## 🔧 **Security**
- ✅ **Password hashing** with PHP's password_hash()
- ✅ **SQL injection protection** with prepared statements
- ✅ **XSS protection** with htmlspecialchars()
- ✅ **Session-based authentication**

## 📱 **Works On**
- ✅ **OVH Shared Hosting**
- ✅ **Any PHP hosting**
- ✅ **Mobile responsive**
- ✅ **All modern browsers**

## 🎉 **Ready to Deploy!**

**Just upload the `php-version` folder to your OVH hosting and run `setup.php`!**

Your app will work perfectly on OVH shared hosting without any "forbidden" errors! 