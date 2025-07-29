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

    public function destroy(Note $note)
    {

        Log::info('Delete attempt', [
            'note_user_id' => $note->user_id,
            'note_user_id_type' => gettype($note->user_id),
            'auth_id' => auth()->id(),
            'auth_id_type' => gettype(auth()->id()),
            'are_equal_loose' => $note->user_id == auth()->id(),
            'are_equal_strict' => $note->user_id === auth()->id(),
        ]);

        if ($note->user_id !== auth()->id()) {
            abort(403, 'Możesz usunąć tylko swoje notatki');
        }

        $note->delete();

        return redirect()->back();
    }
}
