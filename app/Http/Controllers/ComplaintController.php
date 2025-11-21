<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Reporter;

class ComplaintController extends Controller
{
    // Show complaint submission form
    public function create()
    {
        return view('complaints.create');
    }

    // Store complaint in DB
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'required|string',
            'urgency' => 'required|in:low,medium,high',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Save reporter
        $reporter = Reporter::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Handle photo upload
        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('complaints', 'public');
        }

        // Save complaint
        Complaint::create([
            'description' => $request->description,
            'urgency' => $request->urgency,
            'reporter_id' => $reporter->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Complaint submitted successfully!');
    }
}
