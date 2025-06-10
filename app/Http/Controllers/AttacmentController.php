<?php

namespace App\Http\Controllers;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AttacmentController extends Controller
{
public function destroy($id)
{
    $attachment = Attachment::findOrFail($id);

    // Hapus file dari storage
    Storage::delete('public/attachments/' . $attachment->filename);

    // Hapus record dari database
    $attachment->delete();

    return back()->with('success', 'Attachment berhasil dihapus.');
}
}
