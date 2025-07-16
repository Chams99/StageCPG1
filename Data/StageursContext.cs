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
        // Configure DateTime properties to be stored as UTC
        modelBuilder.Entity<Etudiant>()
            .Property(e => e.StartDate)
            .HasConversion(
                v => v.HasValue ? DateTime.SpecifyKind(v.Value, DateTimeKind.Utc) : v,
                v => v);

        modelBuilder.Entity<Etudiant>()
            .Property(e => e.EndDate)
            .HasConversion(
                v => v.HasValue ? DateTime.SpecifyKind(v.Value, DateTimeKind.Utc) : v,
                v => v);
    }
}
