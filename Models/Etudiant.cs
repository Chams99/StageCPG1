namespace StageursApp.Models;

public class Etudiant
{
    public int? Id { get; set; }
    public string? Nom { get; set; }
    public string? Prenom { get; set; }
    public string? Email { get; set; }
    public string? Telephone { get; set; }
    public string? PhotoPath { get; set; }
    public DateTime? StartDate { get; set; }
    public DateTime? EndDate { get; set; }
    public int? SectionId { get; set; }
    public Section? Section { get; set; }
    public int? EncadreurId { get; set; }
    public Encadreur? Encadreur { get; set; }
    public string? BadgePath { get; set; }
}
