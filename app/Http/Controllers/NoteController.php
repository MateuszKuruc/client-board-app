<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'noteable_type' => 'required',
            'noteable_id' => 'required',
            'content' => 'required',
            'edited_at' => 'nullable'
        ]);

        Note::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $note->update($validated);

        return redirect()->back();
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== auth()->id()) {
            abort(403, 'Możesz usunąć tylko swoje notatki');
        }

        $note->delete();

        return redirect()->back();
    }
}
