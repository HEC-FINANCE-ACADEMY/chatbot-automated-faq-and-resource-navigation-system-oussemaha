<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    
    $questions = Question::when($search, function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('question', 'like', '%'.$search.'%')
                  ->orWhere('answer', 'like', '%'.$search.'%');
            });
        })
        ->orderBy('category')
        ->get()
        ->groupBy('category');

    return view('questions.index', compact('questions', 'search'));
}
    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
        ]);
    
        Question::create($validated);
    
        return redirect()->route('questions.create')->with('status', 'Question created successfully!');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'json_file' => 'required|file|mimes:json|max:5120',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
    
        try {
            $file = $request->file('json_file');
            $json = json_decode(file_get_contents($file->path()), true);
    
            // Validate JSON structure
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withErrors(['json_file' => 'Invalid JSON format']);
            }
    
            if (!isset($json['dataset']) || !is_array($json['dataset'])) {
                return redirect()->back()->withErrors(['json_file' => 'Invalid JSON structure - missing dataset array']);
            }
    
            $errors = [];
            $validEntries = [];
    
            foreach ($json['dataset'] as $index => $entry) {
                // Remove ID if present
                unset($entry['id']);
    
                // Validate required fields
                $validator = Validator::make($entry, [
                    'category' => 'required|string|max:255|in:' . implode(',', [
                        'Informations Générales sur l\'Établissement',
                        'Programmes et Cours',
                        'Admission et Inscription',
                        'Vie Étudiante',
                        'Ressources Académiques',
                        'Services de Carrière',
                        'Santé et Bien-être',
                        'Technologie et Innovation',
                        'Politiques et Règlements',
                        'Événements et Actualités',
                        'Site Web et Plateformes en Ligne',
                        'Stages et Expériences Professionnelles',
                        'Professeurs et Encadrement',
                        'Clubs Étudiants et Associations',
                        'Services Administratifs et Carte Étudiante'
                    ]),
                    'question' => 'required|string|max:255',
                    'answer' => 'required|string',
                ]);
    
                if ($validator->fails()) {
                    $errors["Entry " . ($index + 1)] = $validator->errors()->all();
                } else {
                    $validEntries[] = $entry;
                }
            }
    
            if (!empty($errors)) {
                return redirect()->back()->withErrors(['json_errors' => $errors]);
            }
    
            if (empty($validEntries)) {
                return redirect()->back()->withErrors(['json_file' => 'No valid entries found in the JSON file']);
            }
    
            Question::insert($validEntries);
    
            return redirect()->route('questions.create')
                ->with('status', 'Successfully imported ' . count($validEntries) . ' questions!');
    
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['json_file' => 'Error processing file: ' . $e->getMessage()]);
        }
    }

    public function destroy(Question $question)
    {
        try {
            $question->delete();
            return redirect()->back()->with('status', 'Question deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete question.']);
        }
    }
}