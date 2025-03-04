<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\ContactNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactNoteController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'contact_id'    => 'required',
                'title'         => 'required',
                'details'       => 'required',
            ]);

            ContactNote::create([
                'contact_id' => $request->contact_id,
                'title'      => $request->title,
                'details'    => $request->details,
            ]);

            return response()->json([
                'success' => __('note_added_successfully'),
                'notes'   => NoteResource::collection(ContactNote::where('contact_id', $request->contact_id)->get()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $note = ContactNote::find($id);
            $note->delete();

            return response()->json([
                'success' => __('note_deleted_successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
