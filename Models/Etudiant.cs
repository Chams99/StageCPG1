using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using Microsoft.AspNetCore.Mvc;

namespace StageursApp.Models;

public class Etudiant
{
    public int? Id { get; set; }
    
    [Required(ErrorMessage = "Last name is required")]
    [StringLength(50, ErrorMessage = "Last name cannot exceed 50 characters")]
    public string? Nom { get; set; }
    
    [Required(ErrorMessage = "First name is required")]
    [StringLength(50, ErrorMessage = "First name cannot exceed 50 characters")]
    public string? Prenom { get; set; }
    
    [Required(ErrorMessage = "Email is required")]
    [EmailAddress(ErrorMessage = "Invalid email format")]
    public string? Email { get; set; }
    
    [Phone(ErrorMessage = "Invalid phone number format")]
    public string? Telephone { get; set; }
    
    public string? PhotoPath { get; set; }
    
    [NotMapped]
    public IFormFile? PhotoFile { get; set; }
    
    [Display(Name = "Start Date")]
    [DataType(DataType.Date)]
    public DateTime? StartDate { get; set; }
    
    [Display(Name = "End Date")]
    [DataType(DataType.Date)]
    public DateTime? EndDate { get; set; }
    
    [Display(Name = "Section")]
    public int? SectionId { get; set; }
    public Section? Section { get; set; }
    
    [Display(Name = "Supervisor")]
    public int? EncadreurId { get; set; }
    public Encadreur? Encadreur { get; set; }
    
    [HiddenInput(DisplayValue = false)]
    public string? BadgePath { get; set; }
    
    [Required(ErrorMessage = "National ID card number is required")]
    [StringLength(8, MinimumLength = 8, ErrorMessage = "National ID card number must be exactly 8 digits")]
    [RegularExpression(@"^\d{8}$", ErrorMessage = "National ID card number must be exactly 8 digits")]
    [Display(Name = "National ID Card Number")]
    public string IdentificationCardNumber { get; set; }
}
