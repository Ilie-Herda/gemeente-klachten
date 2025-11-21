<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Admin dashboard
    public function index(Request $request)
    {
        $query = Complaint::with('reporter');

        // Simple search: by ID if numeric, otherwise by description text
        if ($request->filled('search')) {
            $search = $request->input('search');

            if (is_numeric($search)) {
                $query->where('id', $search);
            } else {
                $query->where('description', 'like', '%' . $search . '%');
            }
        }

        // Filter by urgency if provided
        if ($request->filled('urgency')) {
            $query->where('urgency', $request->input('urgency'));
        }

        // Paginated complaints for table
        $complaints = $query->latest()->paginate(10)->withQueryString();

        // 5 most recent complaints (for the sidebar list)
        $recent = Complaint::with('reporter')->latest()->take(5)->get();

        // Add an is_overdue property (not stored in DB) for convenience
        foreach ($complaints as $c) {
            $c->is_overdue = (!$c->is_resolved) && $c->created_at->diffInDays(now()) > 14;
        }
        foreach ($recent as $c) {
            $c->is_overdue = (!$c->is_resolved) && $c->created_at->diffInDays(now()) > 14;
        }

        return view('admin.index', compact('complaints', 'recent'));
    }

    // Show a single complaint
    public function show($id)
    {
        $complaint = Complaint::with('reporter')->findOrFail($id);

        return view('admin.show', compact('complaint'));
    }

    // Mark complaint as resolved
    public function resolve($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->is_resolved = true;
        $complaint->save();

        return redirect()
            ->back()
            ->with('success', 'Complaint marked as resolved.');
    }

    // Delete complaint
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Complaint deleted.');
    }

    // Save / update admin note
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->admin_note = $request->note;
        $complaint->save();

        return redirect()
            ->back()
            ->with('success', 'Admin note saved.');
    }
}
