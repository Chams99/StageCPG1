<?php

namespace App\Services;

use App\Models\Etudiant;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;

class BadgeService
{
    /**
     * Generate a PDF badge for a student
     */
    public function generateBadge(Etudiant $etudiant)
    {
        // Load relationships
        $etudiant->load(['section.faculty', 'encadreur']);

        // Generate HTML content
        $html = $this->generateBadgeHtml($etudiant);

        // Configure DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generate filename
        $filename = 'badges/badge_' . $etudiant->identification_card_number . '_' . time() . '.pdf';
        
        // Save PDF to storage
        Storage::disk('public')->put($filename, $dompdf->output());

        // Delete old badge if exists
        if ($etudiant->badge_path && Storage::disk('public')->exists($etudiant->badge_path)) {
            Storage::disk('public')->delete($etudiant->badge_path);
        }

        // Update student record
        $etudiant->update(['badge_path' => $filename]);

        return $filename;
    }

    /**
     * Generate HTML content for the badge
     */
    private function generateBadgeHtml(Etudiant $etudiant)
    {
        $photoUrl = $etudiant->photo_path ? Storage::disk('public')->url($etudiant->photo_path) : null;
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Student Badge - ' . $etudiant->full_name . '</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: #f5f5f5;
                }
                .badge-container {
                    width: 800px;
                    height: 500px;
                    margin: 0 auto;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 15px;
                    padding: 30px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                    position: relative;
                    overflow: hidden;
                }
                .badge-header {
                    text-align: center;
                    color: white;
                    margin-bottom: 30px;
                }
                .badge-header h1 {
                    font-size: 28px;
                    margin: 0;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                }
                .badge-header h2 {
                    font-size: 18px;
                    margin: 10px 0 0 0;
                    font-weight: normal;
                    opacity: 0.9;
                }
                .badge-content {
                    display: flex;
                    align-items: center;
                    background: rgba(255,255,255,0.95);
                    border-radius: 10px;
                    padding: 25px;
                    height: 300px;
                }
                .photo-section {
                    width: 200px;
                    text-align: center;
                    margin-right: 30px;
                }
                .student-photo {
                    width: 150px;
                    height: 150px;
                    border-radius: 50%;
                    border: 4px solid #667eea;
                    object-fit: cover;
                    background-color: #ddd;
                }
                .info-section {
                    flex: 1;
                    color: #333;
                }
                .info-row {
                    margin-bottom: 15px;
                    display: flex;
                    align-items: center;
                }
                .info-label {
                    font-weight: bold;
                    width: 120px;
                    color: #667eea;
                    font-size: 14px;
                }
                .info-value {
                    flex: 1;
                    font-size: 16px;
                }
                .badge-footer {
                    text-align: center;
                    color: white;
                    margin-top: 20px;
                    font-size: 12px;
                    opacity: 0.8;
                }
                .generated-date {
                    position: absolute;
                    bottom: 15px;
                    right: 20px;
                    color: white;
                    font-size: 10px;
                    opacity: 0.7;
                }
            </style>
        </head>
        <body>
            <div class="badge-container">
                <div class="badge-header">
                    <h1>Student Internship Badge</h1>
                    <h2>Professional Identification Card</h2>
                </div>
                
                <div class="badge-content">
                    <div class="photo-section">
                        ' . ($photoUrl ? '<img src="' . $photoUrl . '" class="student-photo" alt="Student Photo">' : '<div class="student-photo"></div>') . '
                    </div>
                    
                    <div class="info-section">
                        <div class="info-row">
                            <div class="info-label">Full Name:</div>
                            <div class="info-value">' . htmlspecialchars($etudiant->full_name) . '</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">ID Number:</div>
                            <div class="info-value">' . htmlspecialchars($etudiant->identification_card_number) . '</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value">' . htmlspecialchars($etudiant->email) . '</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Phone:</div>
                            <div class="info-value">' . htmlspecialchars($etudiant->telephone ?? 'N/A') . '</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Faculty:</div>
                            <div class="info-value">' . htmlspecialchars($etudiant->section?->faculty?->nom ?? 'N/A') . '</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Section:</div>
                            <div class="info-value">' . htmlspecialchars($etudiant->section?->nom ?? 'N/A') . '</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Supervisor:</div>
                            <div class="info-value">' . htmlspecialchars($etudiant->encadreur?->full_name ?? 'N/A') . '</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Internship:</div>
                            <div class="info-value">
                                ' . ($etudiant->start_date ? $etudiant->start_date->format('M d, Y') : 'N/A') . ' - ' . 
                                   ($etudiant->end_date ? $etudiant->end_date->format('M d, Y') : 'N/A') . '
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="badge-footer">
                    This badge is valid for the duration of the internship period
                </div>
                
                <div class="generated-date">
                    Generated: ' . now()->format('M d, Y H:i') . '
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Generate HTML preview for the badge
     */
    public function generateBadgePreview(Etudiant $etudiant)
    {
        return $this->generateBadgeHtml($etudiant);
    }
}
