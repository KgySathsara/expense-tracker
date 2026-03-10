<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->get();
            
        return view('notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'reminder_time' => 'nullable'
        ]);

        Note::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => $request->date],
            [
                'content' => $request->content,
                'reminder_time' => $request->reminder_time
            ]
        );

        return back()->with('success', 'Note saved!');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== auth()->id()) {
            abort(403);
        }
        
        $note->delete();
        
        return back()->with('success', 'Note deleted.');
    }
}
