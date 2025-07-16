using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using StageursApp.Data;
using StageursApp.Models;
using StageursApp.Services;

namespace StageursApp.Controllers
{
    public class AdminController : Controller
    {
        private readonly StageursContext _context;
        private readonly BadgeService _badgeService;

        public AdminController(StageursContext context, BadgeService badgeService)
        {
            _context = context;
            _badgeService = badgeService;
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

        // GET: Admin/EditEncadreur/5
        public async Task<IActionResult> EditEncadreur(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var encadreur = await _context.Encadreurs.FindAsync(id);
            if (encadreur == null)
            {
                return NotFound();
            }

            return View(encadreur);
        }

        // POST: Admin/EditEncadreur/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> EditEncadreur(int id, Encadreur encadreur)
        {
            if (id != encadreur.Id)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                try
                {
                    _context.Update(encadreur);
                    await _context.SaveChangesAsync();
                }
                catch (DbUpdateConcurrencyException)
                {
                    if (!EncadreurExists(encadreur.Id))
                    {
                        return NotFound();
                    }
                    else
                    {
                        throw;
                    }
                }
                return RedirectToAction(nameof(Encadreurs));
            }
            return View(encadreur);
        }

        // GET: Admin/DeleteEncadreur/5
        public async Task<IActionResult> DeleteEncadreur(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var encadreur = await _context.Encadreurs.FindAsync(id);
            if (encadreur == null)
            {
                return NotFound();
            }

            return View(encadreur);
        }

        // POST: Admin/DeleteEncadreur/5
        [HttpPost, ActionName("DeleteEncadreur")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteEncadreurConfirmed(int id)
        {
            var encadreur = await _context.Encadreurs.FindAsync(id);
            if (encadreur != null)
            {
                _context.Encadreurs.Remove(encadreur);
                await _context.SaveChangesAsync();
            }

            return RedirectToAction(nameof(Encadreurs));
        }

        // GET: Admin/EditFaculty/5
        public async Task<IActionResult> EditFaculty(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var faculty = await _context.Faculties.FindAsync(id);
            if (faculty == null)
            {
                return NotFound();
            }

            return View(faculty);
        }

        // POST: Admin/EditFaculty/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> EditFaculty(int id, Faculty faculty)
        {
            if (id != faculty.Id)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                try
                {
                    _context.Update(faculty);
                    await _context.SaveChangesAsync();
                }
                catch (DbUpdateConcurrencyException)
                {
                    if (!FacultyExists(faculty.Id))
                    {
                        return NotFound();
                    }
                    else
                    {
                        throw;
                    }
                }
                return RedirectToAction(nameof(Faculties));
            }
            return View(faculty);
        }

        // GET: Admin/DeleteFaculty/5
        public async Task<IActionResult> DeleteFaculty(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var faculty = await _context.Faculties.FindAsync(id);
            if (faculty == null)
            {
                return NotFound();
            }

            return View(faculty);
        }

        // POST: Admin/DeleteFaculty/5
        [HttpPost, ActionName("DeleteFaculty")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteFacultyConfirmed(int id)
        {
            var faculty = await _context.Faculties.FindAsync(id);
            if (faculty != null)
            {
                _context.Faculties.Remove(faculty);
                await _context.SaveChangesAsync();
            }

            return RedirectToAction(nameof(Faculties));
        }

        // GET: Admin/EditSection/5
        public async Task<IActionResult> EditSection(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var section = await _context.Sections.Include(s => s.Faculty).FirstOrDefaultAsync(s => s.Id == id);
            if (section == null)
            {
                return NotFound();
            }

            ViewBag.Faculties = _context.Faculties.ToList();
            return View(section);
        }

        // POST: Admin/EditSection/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> EditSection(int id, Section section)
        {
            if (id != section.Id)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                try
                {
                    _context.Update(section);
                    await _context.SaveChangesAsync();
                }
                catch (DbUpdateConcurrencyException)
                {
                    if (!SectionExists(section.Id))
                    {
                        return NotFound();
                    }
                    else
                    {
                        throw;
                    }
                }
                return RedirectToAction(nameof(Sections));
            }

            ViewBag.Faculties = _context.Faculties.ToList();
            return View(section);
        }

        // GET: Admin/DeleteSection/5
        public async Task<IActionResult> DeleteSection(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var section = await _context.Sections.Include(s => s.Faculty).FirstOrDefaultAsync(s => s.Id == id);
            if (section == null)
            {
                return NotFound();
            }

            return View(section);
        }

        // POST: Admin/DeleteSection/5
        [HttpPost, ActionName("DeleteSection")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteSectionConfirmed(int id)
        {
            var section = await _context.Sections.FindAsync(id);
            if (section != null)
            {
                _context.Sections.Remove(section);
                await _context.SaveChangesAsync();
            }

            return RedirectToAction(nameof(Sections));
        }

        private bool EncadreurExists(int id)
        {
            return _context.Encadreurs.Any(e => e.Id == id);
        }

        private bool FacultyExists(int? id)
        {
            return _context.Faculties.Any(f => f.Id == id);
        }

        private bool SectionExists(int? id)
        {
            return _context.Sections.Any(s => s.Id == id);
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

        // GET: Admin/GenerateAllBadges
        public async Task<IActionResult> GenerateAllBadges()
        {
            var students = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .ToListAsync();

            var results = new List<object>();
            int successCount = 0;
            int errorCount = 0;

            foreach (var student in students)
            {
                try
                {
                    // Delete old badge if exists
                    if (!string.IsNullOrEmpty(student.BadgePath))
                    {
                        await _badgeService.DeleteBadgeAsync(student.BadgePath);
                    }

                    // Generate new badge
                    student.BadgePath = await _badgeService.GenerateBadgeAsync(student);
                    _context.Update(student);
                    successCount++;
                }
                catch (Exception ex)
                {
                    errorCount++;
                    results.Add(new { student = student, error = ex.Message });
                }
            }

            await _context.SaveChangesAsync();

            ViewBag.SuccessCount = successCount;
            ViewBag.ErrorCount = errorCount;
            ViewBag.Results = results;

            return View();
        }

        // POST: Admin/GenerateMissingBadges
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> GenerateMissingBadges()
        {
            var studentsWithoutBadges = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .Where(e => string.IsNullOrEmpty(e.BadgePath))
                .ToListAsync();

            int successCount = 0;
            int errorCount = 0;

            foreach (var student in studentsWithoutBadges)
            {
                try
                {
                    student.BadgePath = await _badgeService.GenerateBadgeAsync(student);
                    _context.Update(student);
                    successCount++;
                }
                catch (Exception ex)
                {
                    errorCount++;
                }
            }

            await _context.SaveChangesAsync();

            TempData["SuccessMessage"] = $"Generated {successCount} badges successfully. {errorCount} errors occurred.";
            return RedirectToAction("Index");
        }
    }

}
