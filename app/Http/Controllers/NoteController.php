<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class NoteController extends Controller
{
//    notes show

    public function dashboard()
    {
        $notes = Auth::user()->notes()->latest()->get();
        return view('dashboard', compact('notes'));
    }
    
    // notes store

    public function dashboardValue(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Auth::user()->notes()->create([
            'title' => $request->title,
            'description' => $request->description
        ]);
        
        // activity logs record
         ActivityLog::create([
    'user_id' => Auth::id(),
    'action' => 'note_create',
    'ip_address' => $request->ip(),
    'user_agent' => $request->userAgent(),

    ]);

        return redirect()->back()->with('success', 'Note saved successfully');
    }
  
    // note edit page
    public function notesedit(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        return view('notes.edit', compact('note'));
    }
  
    // note update
    public function notesupdate(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $note->title = $request->title;
        $note->description = $request->description;
        $note->save();

    ActivityLog::create([
    'user_id' => Auth::id(),
    'action' => 'note_update',
    'ip_address' => $request->ip(),
    'user_agent' => $request->userAgent(),

    ]);

        return redirect()->route('dashboard')
            ->with('success', 'Note updated successfully');
    }

//   note delete
    public function notesdelete(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }
        $note->delete();
        
        ActivityLog::create([
        'user_id'=>Auth::id(),
        'action'=>'note_delete',
        'ip_address'=>$request->ip(),
        'user_agent'=>$request->userAgent(),
    ]);

        return redirect()->route('dashboard')
            ->with('success', 'Note delete successfully');
    }



    // trash page
    public function showtrash()
    {
          $notes =Note::where('user_id',auth()->id())->onlyTrashed()->latest()->get();
        return view('notes.trash',compact('notes'));
    }
    
    // restore note
    public function restore($id)
    {
        $note = Note::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);
        $note->restore();
        return redirect()->route('notes.trash');

    }
    
    // delete note
    public function forcedelete($id)
    {
        $note = Note::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);
        $note->forceDelete();
        return redirect()->route('notes.trash');

    }

// restore all
public function restoreAll()
{
    $restored = Note::onlyTrashed()
        ->where('user_id', auth()->id())
        ->count();

    Note::onlyTrashed()
        ->where('user_id', auth()->id())
        ->restore();

    return redirect()
        ->route('notes.trash')
        ->with('success', "$restored notes restored successfully.");
}
    
    // delete all
    
     public function forcedeleteall()
    {
        $deleted = Note::onlyTrashed()->where('user_id',auth()->id())->count();
        Note::onlyTrashed()->where('user_id',auth()->id())->forceDelete();
        return redirect()
        ->route('notes.trash')
        ->with('success', "$deleted notes permanently deleted.");

    }








    
}
