using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using StageursApp.Data;
using StageursApp.Models;
using StageursApp.Services;

namespace StageursApp.Controllers
{
    public class EtudiantController : Controller
    {
        private readonly StageursContext _context;
        private readonly BadgeService _badgeService;

        public EtudiantController(StageursContext context, BadgeService badgeService)
        {
            _context = context;
            _badgeService = badgeService;
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
                // Handle photo upload
                if (etudiant.PhotoFile != null)
                {
                    // Validate file size (max 5MB)
                    if (etudiant.PhotoFile.Length > 5 * 1024 * 1024)
                    {
                        ModelState.AddModelError("PhotoFile", "Photo size must be less than 5MB");
                        ViewBag.Encadreurs = _context.Encadreurs.ToList();
                        ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
                        return View(etudiant);
                    }

                    // Validate file type
                    var allowedExtensions = new[] { ".jpg", ".jpeg", ".png", ".gif" };
                    var fileExtension = Path.GetExtension(etudiant.PhotoFile.FileName).ToLowerInvariant();
                    if (!allowedExtensions.Contains(fileExtension))
                    {
                        ModelState.AddModelError("PhotoFile", "Only JPG, JPEG, PNG, and GIF files are allowed");
                        ViewBag.Encadreurs = _context.Encadreurs.ToList();
                        ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
                        return View(etudiant);
                    }

                    // Generate unique filename
                    var fileName = Guid.NewGuid().ToString() + fileExtension;
                    var uploadPath = Path.Combine(Directory.GetCurrentDirectory(), "wwwroot", "uploads", "photos");
                    
                    // Create directory if it doesn't exist
                    if (!Directory.Exists(uploadPath))
                    {
                        Directory.CreateDirectory(uploadPath);
                    }

                    var filePath = Path.Combine(uploadPath, fileName);
                    
                    // Save file
                    using (var stream = new System.IO.FileStream(filePath, System.IO.FileMode.Create))
                    {
                        await etudiant.PhotoFile.CopyToAsync(stream);
                    }

                    etudiant.PhotoPath = "/uploads/photos/" + fileName;
                }

                _context.Etudiants.Add(etudiant);
                await _context.SaveChangesAsync();

                // Generate badge automatically after student is created
                try
                {
                    etudiant.BadgePath = await _badgeService.GenerateBadgeAsync(etudiant);
                    _context.Update(etudiant);
                    await _context.SaveChangesAsync();
                }
                catch (Exception ex)
                {
                    // Log the error but don't fail the student creation
                    // In a production app, you'd want to log this properly
                }

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

        // GET: Etudiant/Edit/5
        public async Task<IActionResult> Edit(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var etudiant = await _context.Etudiants.FindAsync(id);
            if (etudiant == null)
            {
                return NotFound();
            }

            ViewBag.Encadreurs = _context.Encadreurs.ToList();
            ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
            return View(etudiant);
        }

        // POST: Etudiant/Edit/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Edit(int id, Etudiant etudiant)
        {
            if (id != etudiant.Id)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                try
                {
                    // Handle photo upload
                    if (etudiant.PhotoFile != null)
                    {
                        // Validate file size (max 5MB)
                        if (etudiant.PhotoFile.Length > 5 * 1024 * 1024)
                        {
                            ModelState.AddModelError("PhotoFile", "Photo size must be less than 5MB");
                            ViewBag.Encadreurs = _context.Encadreurs.ToList();
                            ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
                            return View(etudiant);
                        }

                        // Validate file type
                        var allowedExtensions = new[] { ".jpg", ".jpeg", ".png", ".gif" };
                        var fileExtension = Path.GetExtension(etudiant.PhotoFile.FileName).ToLowerInvariant();
                        if (!allowedExtensions.Contains(fileExtension))
                        {
                            ModelState.AddModelError("PhotoFile", "Only JPG, JPEG, PNG, and GIF files are allowed");
                            ViewBag.Encadreurs = _context.Encadreurs.ToList();
                            ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
                            return View(etudiant);
                        }

                        // Delete old photo if exists
                        if (!string.IsNullOrEmpty(etudiant.PhotoPath))
                        {
                            var oldPhotoPath = Path.Combine(Directory.GetCurrentDirectory(), "wwwroot", etudiant.PhotoPath.TrimStart('/'));
                            if (System.IO.File.Exists(oldPhotoPath))
                            {
                                System.IO.File.Delete(oldPhotoPath);
                            }
                        }

                        // Generate unique filename
                        var fileName = Guid.NewGuid().ToString() + fileExtension;
                        var uploadPath = Path.Combine(Directory.GetCurrentDirectory(), "wwwroot", "uploads", "photos");
                        
                        // Create directory if it doesn't exist
                        if (!Directory.Exists(uploadPath))
                        {
                            Directory.CreateDirectory(uploadPath);
                        }

                        var filePath = Path.Combine(uploadPath, fileName);
                        
                        // Save file
                        using (var stream = new System.IO.FileStream(filePath, System.IO.FileMode.Create))
                        {
                            await etudiant.PhotoFile.CopyToAsync(stream);
                        }

                        etudiant.PhotoPath = "/uploads/photos/" + fileName;
                    }

                    // Delete old badge if exists
                    if (!string.IsNullOrEmpty(etudiant.BadgePath))
                    {
                        await _badgeService.DeleteBadgeAsync(etudiant.BadgePath);
                    }

                    // Generate new badge
                    etudiant.BadgePath = await _badgeService.GenerateBadgeAsync(etudiant);
                    
                    _context.Update(etudiant);
                    await _context.SaveChangesAsync();
                }
                catch (DbUpdateConcurrencyException)
                {
                    if (!EtudiantExists(etudiant.Id))
                    {
                        return NotFound();
                    }
                    else
                    {
                        throw;
                    }
                }
                return RedirectToAction(nameof(Index));
            }

            ViewBag.Encadreurs = _context.Encadreurs.ToList();
            ViewBag.Sections = _context.Sections.Include(s => s.Faculty).ToList();
            return View(etudiant);
        }

        // GET: Etudiant/Delete/5
        public async Task<IActionResult> Delete(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var etudiant = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (etudiant == null)
            {
                return NotFound();
            }

            return View(etudiant);
        }

        // POST: Etudiant/Delete/5
        [HttpPost, ActionName("Delete")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteConfirmed(int id)
        {
            var etudiant = await _context.Etudiants.FindAsync(id);
            if (etudiant != null)
            {
                // Delete photo file if exists
                if (!string.IsNullOrEmpty(etudiant.PhotoPath))
                {
                    var photoPath = Path.Combine(Directory.GetCurrentDirectory(), "wwwroot", etudiant.PhotoPath.TrimStart('/'));
                    if (System.IO.File.Exists(photoPath))
                    {
                        System.IO.File.Delete(photoPath);
                    }
                }

                // Delete badge file if exists
                if (!string.IsNullOrEmpty(etudiant.BadgePath))
                {
                    await _badgeService.DeleteBadgeAsync(etudiant.BadgePath);
                }

                _context.Etudiants.Remove(etudiant);
                await _context.SaveChangesAsync();
            }

            return RedirectToAction(nameof(Index));
        }

        // GET: Etudiant/Details/5
        public async Task<IActionResult> Details(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var etudiant = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (etudiant == null)
            {
                return NotFound();
            }

            return View(etudiant);
        }

        // GET: Etudiant/DownloadBadge/5
        public async Task<IActionResult> DownloadBadge(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var etudiant = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (etudiant == null)
            {
                return NotFound();
            }

            try
            {
                // Generate badge if it doesn't exist
                if (string.IsNullOrEmpty(etudiant.BadgePath))
                {
                    etudiant.BadgePath = await _badgeService.GenerateBadgeAsync(etudiant);
                    _context.Update(etudiant);
                    await _context.SaveChangesAsync();
                }

                var pdfBytes = await _badgeService.GetBadgePdfBytesAsync(etudiant.BadgePath);
                if (pdfBytes == null)
                {
                    return NotFound();
                }

                return File(pdfBytes, "application/pdf", $"badge_{etudiant.Prenom}_{etudiant.Nom}.pdf");
            }
            catch (IOException ex) when (ex.Message.Contains("being used by another process"))
            {
                // Log the error and return a user-friendly message
                TempData["ErrorMessage"] = "The badge file is currently being used by another process. Please try again in a few moments.";
                return RedirectToAction("Details", new { id = etudiant.Id });
            }
            catch (Exception ex)
            {
                // Log the error and return a user-friendly message
                TempData["ErrorMessage"] = "An error occurred while downloading the badge. Please try again.";
                return RedirectToAction("Details", new { id = etudiant.Id });
            }
        }

        // GET: Etudiant/PrintBadge/5
        public async Task<IActionResult> PrintBadge(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var etudiant = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (etudiant == null)
            {
                return NotFound();
            }

            try
            {
                // Generate badge if it doesn't exist
                if (string.IsNullOrEmpty(etudiant.BadgePath))
                {
                    etudiant.BadgePath = await _badgeService.GenerateBadgeAsync(etudiant);
                    _context.Update(etudiant);
                    await _context.SaveChangesAsync();
                }

                var pdfBytes = await _badgeService.GetBadgePdfBytesAsync(etudiant.BadgePath);
                if (pdfBytes == null)
                {
                    return NotFound();
                }

                // Return PDF with print-friendly headers
                Response.Headers["Content-Disposition"] = "inline; filename=badge.pdf";
                return File(pdfBytes, "application/pdf");
            }
            catch (IOException ex) when (ex.Message.Contains("being used by another process"))
            {
                // Log the error and return a user-friendly message
                TempData["ErrorMessage"] = "The badge file is currently being used by another process. Please try again in a few moments.";
                return RedirectToAction("Details", new { id = etudiant.Id });
            }
            catch (Exception ex)
            {
                // Log the error and return a user-friendly message
                TempData["ErrorMessage"] = "An error occurred while printing the badge. Please try again.";
                return RedirectToAction("Details", new { id = etudiant.Id });
            }
        }

        // GET: Etudiant/PreviewBadge/5
        public async Task<IActionResult> PreviewBadge(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var etudiant = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (etudiant == null)
            {
                return NotFound();
            }

            var badgeHtml = _badgeService.GenerateBadgePreviewHtml(etudiant);
            ViewBag.BadgeHtml = badgeHtml;
            ViewBag.Etudiant = etudiant;

            return View();
        }

        // POST: Etudiant/RegenerateBadge/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> RegenerateBadge(int id)
        {
            var etudiant = await _context.Etudiants
                .Include(e => e.Encadreur)
                .Include(e => e.Section)
                .ThenInclude(s => s.Faculty)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (etudiant == null)
            {
                return NotFound();
            }

            // Delete old badge if exists
            if (!string.IsNullOrEmpty(etudiant.BadgePath))
            {
                await _badgeService.DeleteBadgeAsync(etudiant.BadgePath);
            }

            // Generate new badge
            etudiant.BadgePath = await _badgeService.GenerateBadgeAsync(etudiant);
            _context.Update(etudiant);
            await _context.SaveChangesAsync();

            TempData["SuccessMessage"] = "Badge regenerated successfully!";
            return RedirectToAction("Details", new { id = etudiant.Id });
        }

        private bool EtudiantExists(int? id)
        {
            return _context.Etudiants.Any(e => e.Id == id);
        }
    }
}
