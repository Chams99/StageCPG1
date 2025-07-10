using StageursApp.Models;

namespace StageursApp.Models
{
    public class AdminDashboardViewModel
    {
        public List<Faculty> Faculties { get; set; }
        public List<Section> Sections { get; set; }
        public List<Encadreur> Encadreurs { get; set; }
        public List<Etudiant> Etudiants { get; set; }
    }
}