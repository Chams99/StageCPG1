# 🔒 SECURE DEPLOYMENT GUIDE - OVH Hosting

## ✅ **SECURITY FIXES APPLIED**

Your passwords are now **SAFE**! Here's what I fixed:

### 🔐 **Database Password**
- ❌ **BEFORE**: `Password=cpg1` (hardcoded in files)
- ✅ **AFTER**: `Password=${DB_PASSWORD}` (environment variable)

### 👤 **Admin Password** 
- ❌ **BEFORE**: `"admin123"` (hardcoded in code)
- ✅ **AFTER**: Uses `ADMIN_PASSWORD` environment variable

---

## 🚀 **DEPLOYMENT STEPS**

### 1. **Set Environment Variables on OVH**

**Option A: OVH Control Panel (Recommended)**
- Go to your OVH hosting control panel
- Find "Environment Variables" or "App Settings"
- Add these variables:
  ```
  DB_PASSWORD=your_actual_postgres_password
  ADMIN_PASSWORD=your_secure_admin_password
  ASPNETCORE_ENVIRONMENT=Production
  ```

**Option B: Create .env file on server**
- Create a `.env` file on your OVH server with:
  ```
  DB_PASSWORD=your_actual_postgres_password
  ADMIN_PASSWORD=your_secure_admin_password
  ASPNETCORE_ENVIRONMENT=Production
  ```

### 2. **Upload Files via FileZilla**
Upload the entire `deploy` folder contents to your OVH web root.

### 3. **Database Migration**
Run this command on your OVH server:
```bash
dotnet ef database update
```

---

## 🛡️ **SECURITY CHECKLIST**

- ✅ Passwords removed from code files
- ✅ Environment variables used for secrets
- ✅ `.env` files added to `.gitignore`
- ✅ HTTPS enabled in production
- ✅ Authentication configured

---

## 📁 **Files to Upload**
The `deploy` folder contains:
- ✅ `StageursApp.dll` (your app)
- ✅ `wwwroot/` (CSS, JS, images)
- ✅ `appsettings.json` (secure config)
- ✅ `web.config` (IIS config)
- ✅ All dependencies

**Your passwords are now safe!** 🎉 