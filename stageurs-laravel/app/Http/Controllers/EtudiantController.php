<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Section;
use App\Models\Encadreur;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EtudiantController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->middleware('auth:admin');
        $this->badgeService = $badgeService;
    }

    /**
     * Display a listing of students
     */
    public function index()
    {
        $etudiants = Etudiant::with(['section.faculty', 'encadreur'])
            ->latest()
            ->paginate(10);

        return view('etudiants.index', compact('etudiants'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $sections = Section::with('faculty')->get();
        $encadreurs = Encadreur::all();
        
        return view('etudiants.create', compact('sections', 'encadreurs'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Etudiant::rules());
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $photoPath;
        }

        $etudiant = Etudiant::create($validated);

        // Generate badge
        try {
            $this->badgeService->generateBadge($etudiant);
        } catch (\Exception $e) {
            \Log::error("Failed to generate badge for new student {$etudiant->id}: " . $e->getMessage());
        }

        return redirect()->route('etudiants.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student
     */
    public function show(Etudiant $etudiant)
    {
        $etudiant->load(['section.faculty', 'encadreur']);
        return view('etudiants.show', compact('etudiant'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Etudiant $etudiant)
    {
        $sections = Section::with('faculty')->get();
        $encadreurs = Encadreur::all();
        
        return view('etudiants.edit', compact('etudiant', 'sections', 'encadreurs'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Etudiant $etudiant)
    {
        $validated = $request->validate(Etudiant::rules($etudiant->id));
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($etudiant->photo_path) {
                Storage::disk('public')->delete($etudiant->photo_path);
            }
            
            $photoPath = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $photoPath;
        }

        $etudiant->update($validated);

        // Regenerate badge
        try {
            $this->badgeService->generateBadge($etudiant);
        } catch (\Exception $e) {
            \Log::error("Failed to regenerate badge for student {$etudiant->id}: " . $e->getMessage());
        }

        return redirect()->route('etudiants.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Etudiant $etudiant)
    {
        // Delete photo if exists
        if ($etudiant->photo_path) {
            Storage::disk('public')->delete($etudiant->photo_path);
        }

        // Delete badge if exists
        if ($etudiant->badge_path) {
            Storage::disk('public')->delete($etudiant->badge_path);
        }

        $etudiant->delete();

        return redirect()->route('etudiants.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Download student badge
     */
    public function downloadBadge(Etudiant $etudiant)
    {
        if (!$etudiant->badge_path || !Storage::disk('public')->exists($etudiant->badge_path)) {
            return back()->with('error', 'Badge not found.');
        }

        return Storage::disk('public')->download($etudiant->badge_path, "badge_{$etudiant->identification_card_number}.pdf");
    }

    /**
     * Preview student badge
     */
    public function previewBadge(Etudiant $etudiant)
    {
        if (!$etudiant->badge_path || !Storage::disk('public')->exists($etudiant->badge_path)) {
            return back()->with('error', 'Badge not found.');
        }

        return response()->file(storage_path('app/public/' . $etudiant->badge_path));
    }

    /**
     * Regenerate student badge
     */
    public function regenerateBadge(Etudiant $etudiant)
    {
        try {
            $this->badgeService->generateBadge($etudiant);
            return back()->with('success', 'Badge regenerated successfully.');
        } catch (\Exception $e) {
            \Log::error("Failed to regenerate badge for student {$etudiant->id}: " . $e->getMessage());
            return back()->with('error', 'Failed to regenerate badge.');
        }
    }

    /**
     * Get sections for a faculty (AJAX)
     */
    public function getSections(Request $request)
    {
        $facultyId = $request->get('faculty_id');
        $sections = Section::where('faculty_id', $facultyId)->get();
        
        return response()->json($sections);
    }
}
