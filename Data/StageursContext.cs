using Microsoft.EntityFrameworkCore;
using StageursApp.Models;

public class StageursContext : DbContext
{
    public StageursContext(DbContextOptions<StageursContext> options) : base(options) {}

    public DbSet<Etudiant> Etudiants { get; set; }
    public DbSet<Encadreur> Encadreurs { get; set; }
    public DbSet<Section> Sections { get; set; }
    public DbSet<Faculty> Faculties { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        // Fluent API if needed
    }
}
