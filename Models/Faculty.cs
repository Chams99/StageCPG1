namespace StageursApp.Models;

public class Faculty
{
    public int? Id { get; set; }
    public string? Nom { get; set; }

    // Navigation
    public ICollection<Section>? Sections { get; set; }
}
