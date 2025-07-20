using System.ComponentModel.DataAnnotations;

namespace StageursApp.Models
{
    public class Admin
    {
        public int Id { get; set; }
        
        [Required]
        [StringLength(50)]
        public string Username { get; set; }
        
        [Required]
        [StringLength(100)]
        public string Password { get; set; }
        
        public DateTime CreatedAt { get; set; } = DateTime.Now;
    }
} 