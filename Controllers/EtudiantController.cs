using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using StageursApp.Data;
using StageursApp.Models;

namespace StageursApp.Controllers
{
    public class EtudiantController : Controller
    {
        private readonly StageursContext _context;

        public EtudiantController(StageursContext context)
        {
            _context = context;
        }

        public IActionResult Create()
        {
            ViewBag.Encadreurs = _context.Encadreurs.ToList();
            ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> Create(Etudiant etudiant)
        {
            if (ModelState.IsValid)
            {
                _context.Etudiants.Add(etudiant);
                await _context.SaveChangesAsync();
                return RedirectToAction("Index");
            }

            ViewBag.Encadreurs = _context.Encadreurs.ToList();
            ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
            return View(etudiant);
        }

        public IActionResult Index()
        {
            var etudiants = _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .ToList();

            return View(etudiants);
        }
    }
}
