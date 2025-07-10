using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using StageursApp.Data;
using StageursApp.Models;

namespace StageursApp.Controllers
{
    public class AdminController : Controller
    {
        private readonly StageursContext _context;

        public AdminController(StageursContext context)
        {
            _context = context;
        }

        // Faculté
        public IActionResult Faculties() => View(_context.Faculties.ToList());

        [HttpPost]
        public IActionResult AddFaculty(string nom)
        {
            if (!string.IsNullOrEmpty(nom))
            {
                _context.Faculties.Add(new Faculty { Nom = nom });
                _context.SaveChanges();
            }
            return RedirectToAction("Faculties");
        }

        // Section
        public IActionResult Sections()
        {
            ViewBag.Faculties = _context.Faculties.ToList();
            return View(_context.Sections.Include(s => s.Faculty).ToList());
        }

        [HttpPost]
        public IActionResult AddSection(string nom, int facultyId)
        {
            if (!string.IsNullOrEmpty(nom))
            {
                _context.Sections.Add(new Section { Nom = nom, FacultyId = facultyId });
                _context.SaveChanges();
            }
            return RedirectToAction("Sections");
        }

        // Encadreur
        public IActionResult Encadreurs() => View(_context.Encadreurs.ToList());

        [HttpPost]
        public IActionResult AddEncadreur(Encadreur e)
        {
            if (ModelState.IsValid)
            {
                _context.Encadreurs.Add(e);
                _context.SaveChanges();
            }
            return RedirectToAction("Encadreurs");
        }
        public IActionResult Index()
        {
            var model = new AdminDashboardViewModel
            {
                Faculties = _context.Faculties.ToList(),
                Sections = _context.Sections.Include(s => s.Faculty).ToList(),
                Encadreurs = _context.Encadreurs.ToList(),
                Etudiants = _context.Etudiants
                    .Include(e => e.Encadreur)
                    .Include(e => e.Section)
                    .ThenInclude(s => s.Faculty)
                    .ToList()
            };
            return View(model);
        }
    }

}
