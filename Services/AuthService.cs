using Microsoft.EntityFrameworkCore;
using StageursApp.Data;
using StageursApp.Models;
using System.Security.Cryptography;
using System.Text;

namespace StageursApp.Services
{
    public class AuthService
    {
        private readonly StageursContext _context;

        public AuthService(StageursContext context)
        {
            _context = context;
        }

        public async Task<Admin> ValidateAdminAsync(string username, string password)
        {
            var admin = await _context.Admins.FirstOrDefaultAsync(a => a.Username == username);
            
            if (admin != null && VerifyPassword(password, admin.Password))
            {
                return admin;
            }
            
            return null;
        }

        public async Task<bool> CreateAdminAsync(string username, string password)
        {
            if (await _context.Admins.AnyAsync(a => a.Username == username))
            {
                return false; // Username already exists
            }

            var hashedPassword = HashPassword(password);
            var admin = new Admin
            {
                Username = username,
                Password = hashedPassword,
                CreatedAt = DateTime.UtcNow
            };

            _context.Admins.Add(admin);
            await _context.SaveChangesAsync();
            return true;
        }

        private string HashPassword(string password)
        {
            using (var sha256 = SHA256.Create())
            {
                var hashedBytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(password));
                return Convert.ToBase64String(hashedBytes);
            }
        }

        private bool VerifyPassword(string inputPassword, string storedHash)
        {
            var inputHash = HashPassword(inputPassword);
            return inputHash == storedHash;
        }

        public async Task<bool> InitializeDefaultAdminAsync()
        {
            if (!await _context.Admins.AnyAsync())
            {
                var adminPassword = Environment.GetEnvironmentVariable("ADMIN_PASSWORD") ?? "admin123";
                return await CreateAdminAsync("admin", adminPassword);
            }
            return false;
        }
    }
} 