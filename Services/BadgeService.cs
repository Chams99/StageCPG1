using iTextSharp.text;
using iTextSharp.text.pdf;
using StageursApp.Models;

namespace StageursApp.Services
{
    public class BadgeService
    {
        private readonly IWebHostEnvironment _environment;

        public BadgeService(IWebHostEnvironment environment)
        {
            _environment = environment;
        }

        public async Task<string> GenerateBadgeAsync(Etudiant etudiant)
        {
            // Create badges directory if it doesn't exist
            var badgesPath = Path.Combine(_environment.WebRootPath, "uploads", "badges");
            if (!Directory.Exists(badgesPath))
            {
                Directory.CreateDirectory(badgesPath);
            }

            // Generate unique filename
            var fileName = $"badge_{etudiant.Id}_{Guid.NewGuid()}.pdf";
            var filePath = Path.Combine(badgesPath, fileName);

            // Create PDF document with proper file handling
            var document = new Document(PageSize.A4, 20, 20, 20, 20);
            FileStream? fileStream = null;
            PdfWriter? writer = null;

            try
            {
                fileStream = new FileStream(filePath, FileMode.Create, FileAccess.Write, FileShare.None);
                writer = PdfWriter.GetInstance(document, fileStream);
                document.Open();

                // Add content to PDF
                AddBadgeContentAsync(document, etudiant);
            }
            finally
            {
                // Ensure proper cleanup
                if (document.IsOpen())
                {
                    document.Close();
                }
                writer?.Close();
                fileStream?.Dispose();
            }

            // Small delay to ensure file is fully written
            await Task.Delay(100);

            // Return the relative path for database storage
            return $"/uploads/badges/{fileName}";
        }

        private void AddBadgeContentAsync(Document document, Etudiant etudiant)
        {
            // Create title
            var titleFont = FontFactory.GetFont(FontFactory.HELVETICA_BOLD, 24, BaseColor.Black);
            var title = new Paragraph("STUDENT BADGE", titleFont);
            title.Alignment = Element.ALIGN_CENTER;
            title.SpacingAfter = 20f;
            document.Add(title);

            // Add student photo if available
            if (!string.IsNullOrEmpty(etudiant.PhotoPath))
            {
                var photoPath = Path.Combine(_environment.WebRootPath, etudiant.PhotoPath.TrimStart('/'));
                if (File.Exists(photoPath))
                {
                    try
                    {
                        var image = Image.GetInstance(photoPath);
                        image.ScaleToFit(100, 100);
                        image.Alignment = Element.ALIGN_CENTER;
                        image.SpacingAfter = 15f;
                        document.Add(image);
                    }
                    catch (Exception)
                    {
                        // If image loading fails, add a placeholder text
                        var placeholderFont = FontFactory.GetFont(FontFactory.HELVETICA, 10, BaseColor.Gray);
                        var placeholder = new Paragraph("Photo not available", placeholderFont);
                        placeholder.Alignment = Element.ALIGN_CENTER;
                        placeholder.SpacingAfter = 15f;
                        document.Add(placeholder);
                    }
                }
            }

            // Create content table
            var table = new PdfPTable(2);
            table.WidthPercentage = 80;
            table.HorizontalAlignment = Element.ALIGN_CENTER;

            // Add student information
            var labelFont = FontFactory.GetFont(FontFactory.HELVETICA_BOLD, 12, BaseColor.Black);
            var valueFont = FontFactory.GetFont(FontFactory.HELVETICA, 12, BaseColor.Black);

            AddTableRow(table, "Full Name:", $"{etudiant.Prenom ?? ""} {etudiant.Nom ?? ""}".Trim(), labelFont, valueFont);
            AddTableRow(table, "National ID:", etudiant.IdentificationCardNumber ?? "N/A", labelFont, valueFont);
            AddTableRow(table, "Email:", etudiant.Email ?? "N/A", labelFont, valueFont);
            AddTableRow(table, "Phone:", etudiant.Telephone ?? "N/A", labelFont, valueFont);
            AddTableRow(table, "Supervisor:", etudiant.Encadreur != null ? $"{etudiant.Encadreur.Prenom} {etudiant.Encadreur.Nom}" : "Not assigned", labelFont, valueFont);
            AddTableRow(table, "Section:", etudiant.Section?.Nom ?? "Not assigned", labelFont, valueFont);
            AddTableRow(table, "Faculty:", etudiant.Section?.Faculty?.Nom ?? "Not assigned", labelFont, valueFont);
            
            if (etudiant.StartDate.HasValue)
            {
                AddTableRow(table, "Start Date:", etudiant.StartDate.Value.ToString("MMM dd, yyyy"), labelFont, valueFont);
            }
            
            if (etudiant.EndDate.HasValue)
            {
                AddTableRow(table, "End Date:", etudiant.EndDate.Value.ToString("MMM dd, yyyy"), labelFont, valueFont);
            }

            document.Add(table);

            // Add footer
            var footerFont = FontFactory.GetFont(FontFactory.HELVETICA, 10, BaseColor.Gray);
            var footer = new Paragraph($"Generated on {DateTime.Now:MMM dd, yyyy 'at' HH:mm}", footerFont);
            footer.Alignment = Element.ALIGN_CENTER;
            footer.SpacingBefore = 30f;
            document.Add(footer);
        }

        private void AddTableRow(PdfPTable table, string label, string value, Font labelFont, Font valueFont)
        {
            var labelCell = new PdfPCell(new Phrase(label, labelFont));
            var valueCell = new PdfPCell(new Phrase(value, valueFont));
            
            labelCell.Border = Rectangle.NO_BORDER;
            valueCell.Border = Rectangle.NO_BORDER;
            labelCell.PaddingBottom = 5f;
            valueCell.PaddingBottom = 5f;
            
            table.AddCell(labelCell);
            table.AddCell(valueCell);
        }

        public async Task<byte[]?> GetBadgePdfBytesAsync(string badgePath)
        {
            if (string.IsNullOrEmpty(badgePath))
                return null;

            var fullPath = Path.Combine(_environment.WebRootPath, badgePath.TrimStart('/'));
            if (!File.Exists(fullPath))
                return null;

            // Retry logic for file access with exponential backoff
            const int maxRetries = 3;
            const int baseDelayMs = 100;

            for (int attempt = 0; attempt < maxRetries; attempt++)
            {
                try
                {
                    // Use FileShare.Read to allow other processes to read the file
                    using var fileStream = new FileStream(fullPath, FileMode.Open, FileAccess.Read, FileShare.Read);
                    using var memoryStream = new MemoryStream();
                    await fileStream.CopyToAsync(memoryStream);
                    return memoryStream.ToArray();
                }
                catch (IOException ex) when (ex.Message.Contains("being used by another process"))
                {
                    if (attempt == maxRetries - 1)
                    {
                        // Last attempt failed, throw the exception
                        throw;
                    }

                    // Wait with exponential backoff before retrying
                    var delayMs = baseDelayMs * (int)Math.Pow(2, attempt);
                    await Task.Delay(delayMs);
                }
                catch (Exception)
                {
                    // For other exceptions, don't retry
                    throw;
                }
            }

            return null;
        }

        public async Task DeleteBadgeAsync(string badgePath)
        {
            if (string.IsNullOrEmpty(badgePath))
                return;

            var fullPath = Path.Combine(_environment.WebRootPath, badgePath.TrimStart('/'));
            if (File.Exists(fullPath))
            {
                // Retry logic for file deletion with exponential backoff
                const int maxRetries = 3;
                const int baseDelayMs = 100;

                for (int attempt = 0; attempt < maxRetries; attempt++)
                {
                    try
                    {
                        await Task.Run(() => File.Delete(fullPath));
                        return; // Successfully deleted
                    }
                    catch (IOException ex) when (ex.Message.Contains("being used by another process"))
                    {
                        if (attempt == maxRetries - 1)
                        {
                            // Last attempt failed, throw the exception
                            throw;
                        }

                        // Wait with exponential backoff before retrying
                        var delayMs = baseDelayMs * (int)Math.Pow(2, attempt);
                        await Task.Delay(delayMs);
                    }
                    catch (Exception)
                    {
                        // For other exceptions, don't retry
                        throw;
                    }
                }
            }
        }

        public string GenerateBadgePreviewHtml(Etudiant etudiant)
        {
            var html = $@"
                <div class='badge-preview' style='border: 2px solid #007bff; border-radius: 10px; padding: 20px; max-width: 400px; margin: 0 auto; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h3 style='color: #007bff; margin: 0; font-weight: bold;'>STUDENT BADGE</h3>
                    </div>
                    
                    <div style='text-align: center; margin-bottom: 20px;'>
                        {(string.IsNullOrEmpty(etudiant.PhotoPath) ? 
                            "<div style='width: 80px; height: 80px; background: #dee2e6; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;'><i class='fas fa-user' style='font-size: 2rem; color: #6c757d;'></i></div>" :
                            $"<img src='{etudiant.PhotoPath}' style='width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff;' alt='Student Photo' />")}
                    </div>
                    
                    <div style='margin-bottom: 15px;'>
                        <strong style='color: #495057;'>Name:</strong> {etudiant.Prenom ?? ""} {etudiant.Nom ?? ""}
                    </div>
                    
                    <div style='margin-bottom: 15px;'>
                        <strong style='color: #495057;'>National ID:</strong> {etudiant.IdentificationCardNumber ?? "N/A"}
                    </div>
                    
                    <div style='margin-bottom: 15px;'>
                        <strong style='color: #495057;'>Email:</strong> {etudiant.Email ?? "N/A"}
                    </div>
                    
                    <div style='margin-bottom: 15px;'>
                        <strong style='color: #495057;'>Phone:</strong> {etudiant.Telephone ?? "N/A"}
                    </div>
                    
                    <div style='margin-bottom: 15px;'>
                        <strong style='color: #495057;'>Supervisor:</strong> {(etudiant.Encadreur != null ? $"{etudiant.Encadreur.Prenom} {etudiant.Encadreur.Nom}" : "Not assigned")}
                    </div>
                    
                    <div style='margin-bottom: 15px;'>
                        <strong style='color: #495057;'>Section:</strong> {etudiant.Section?.Nom ?? "Not assigned"}
                    </div>
                    
                    <div style='margin-bottom: 15px;'>
                        <strong style='color: #495057;'>Faculty:</strong> {etudiant.Section?.Faculty?.Nom ?? "Not assigned"}
                    </div>
                    
                    {(etudiant.StartDate.HasValue ? $"<div style='margin-bottom: 15px;'><strong style='color: #495057;'>Start Date:</strong> {etudiant.StartDate.Value.ToString("MMM dd, yyyy")}</div>" : "")}
                    {(etudiant.EndDate.HasValue ? $"<div style='margin-bottom: 15px;'><strong style='color: #495057;'>End Date:</strong> {etudiant.EndDate.Value.ToString("MMM dd, yyyy")}</div>" : "")}
                    
                    <div style='text-align: center; margin-top: 20px; font-size: 0.8em; color: #6c757d;'>
                        Generated on {DateTime.Now:MMM dd, yyyy 'at' HH:mm}
                    </div>
                </div>";

            return html;
        }
    }
} 