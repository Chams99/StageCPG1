namespace StageursApp.Models;

public class Section
{
    public int? Id { get; set; }
    public string? Nom { get; set; }
    public int? FacultyId { get; set; }

    // Navigation
    public Faculty? Faculty { get; set; }
    public ICollection<Etudiant>? Etudiants { get; set; }
}
