namespace StageursApp.Models;

public class Encadreur
{
    public int Id { get; set; }
    public string? Nom { get; set; }
    public string? Prenom { get; set; }
    public string? Email { get; set; }
    public string? Fonction { get; set; }
    public string? Telephone { get; set; }

    // Navigation
    public ICollection<Etudiant>? Etudiants { get; set; }
}
