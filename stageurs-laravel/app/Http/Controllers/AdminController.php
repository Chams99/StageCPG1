<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Etudiant;
use App\Models\Faculty;
use App\Models\Section;
use App\Models\Encadreur;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->middleware('auth:admin');
        $this->badgeService = $badgeService;
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => Etudiant::count(),
            'total_faculties' => Faculty::count(),
            'total_sections' => Section::count(),
            'total_supervisors' => Encadreur::count(),
            'students_with_badges' => Etudiant::whereNotNull('badge_path')->count(),
            'students_without_badges' => Etudiant::whereNull('badge_path')->count(),
        ];

        $recent_students = Etudiant::with(['section.faculty', 'encadreur'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_students'));
    }

    /**
     * Show faculties management
     */
    public function faculties()
    {
        $faculties = Faculty::withCount('sections')->get();
        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Store new faculty
     */
    public function storeFaculty(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255'
        ]);

        Faculty::create($request->only('nom'));
        return redirect()->route('admin.faculties')->with('success', 'Faculty created successfully.');
    }

    /**
     * Update faculty
     */
    public function updateFaculty(Request $request, Faculty $faculty)
    {
        $request->validate([
            'nom' => 'required|string|max:255'
        ]);

        $faculty->update($request->only('nom'));
        return redirect()->route('admin.faculties')->with('success', 'Faculty updated successfully.');
    }

    /**
     * Delete faculty
     */
    public function deleteFaculty(Faculty $faculty)
    {
        $faculty->delete();
        return redirect()->route('admin.faculties')->with('success', 'Faculty deleted successfully.');
    }

    /**
     * Show sections management
     */
    public function sections()
    {
        $sections = Section::with('faculty')->get();
        $faculties = Faculty::all();
        return view('admin.sections.index', compact('sections', 'faculties'));
    }

    /**
     * Store new section
     */
    public function storeSection(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        Section::create($request->only(['nom', 'faculty_id']));
        return redirect()->route('admin.sections')->with('success', 'Section created successfully.');
    }

    /**
     * Update section
     */
    public function updateSection(Request $request, Section $section)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        $section->update($request->only(['nom', 'faculty_id']));
        return redirect()->route('admin.sections')->with('success', 'Section updated successfully.');
    }

    /**
     * Delete section
     */
    public function deleteSection(Section $section)
    {
        $section->delete();
        return redirect()->route('admin.sections')->with('success', 'Section deleted successfully.');
    }

    /**
     * Show supervisors management
     */
    public function encadreurs()
    {
        $encadreurs = Encadreur::withCount('etudiants')->get();
        return view('admin.encadreurs.index', compact('encadreurs'));
    }

    /**
     * Store new supervisor
     */
    public function storeEncadreur(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'fonction' => 'required|string|max:255',
            'telephone' => 'required|string|max:255'
        ]);

        Encadreur::create($request->only(['nom', 'prenom', 'email', 'fonction', 'telephone']));
        return redirect()->route('admin.encadreurs')->with('success', 'Supervisor created successfully.');
    }

    /**
     * Update supervisor
     */
    public function updateEncadreur(Request $request, Encadreur $encadreur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'fonction' => 'required|string|max:255',
            'telephone' => 'required|string|max:255'
        ]);

        $encadreur->update($request->only(['nom', 'prenom', 'email', 'fonction', 'telephone']));
        return redirect()->route('admin.encadreurs')->with('success', 'Supervisor updated successfully.');
    }

    /**
     * Delete supervisor
     */
    public function deleteEncadreur(Encadreur $encadreur)
    {
        $encadreur->delete();
        return redirect()->route('admin.encadreurs')->with('success', 'Supervisor deleted successfully.');
    }

    /**
     * Generate badges for all students
     */
    public function generateAllBadges()
    {
        $students = Etudiant::with(['section.faculty', 'encadreur'])->get();
        $generated = 0;

        foreach ($students as $student) {
            try {
                $this->badgeService->generateBadge($student);
                $generated++;
            } catch (\Exception $e) {
                // Log error but continue with other students
                \Log::error("Failed to generate badge for student {$student->id}: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', "Generated badges for {$generated} students.");
    }

    /**
     * Generate badges for students without badges
     */
    public function generateMissingBadges()
    {
        $students = Etudiant::whereNull('badge_path')
            ->with(['section.faculty', 'encadreur'])
            ->get();
        
        $generated = 0;

        foreach ($students as $student) {
            try {
                $this->badgeService->generateBadge($student);
                $generated++;
            } catch (\Exception $e) {
                \Log::error("Failed to generate badge for student {$student->id}: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', "Generated badges for {$generated} students.");
    }
}
