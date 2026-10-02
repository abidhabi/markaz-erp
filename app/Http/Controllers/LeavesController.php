<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use Carbon\Carbon;

class LeavesController extends Controller
{
    public function index()
    {
        return view('pages.leaves.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'duration' => 'required|numeric|min:0.5',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        LeaveApplication::create([
            'user_id' => auth()->id(),
            'leave_type' => $request->leave_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'duration' => $request->duration,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    public function calendarEvents()
    {
        $leaves = LeaveApplication::where('user_id', auth()->id())
            ->get()
            ->map(function($leave) {
                return [
                    'title' => $leave->leave_type . ' Leave (' . $leave->status . ')',
                    'start' => $leave->from_date,
                    'end' => Carbon::parse($leave->to_date)->addDay()->toDateString(),
                    'color' => $leave->status == 'Approved' ? '#10b981' : ($leave->status == 'Rejected' ? '#ef4444' : '#f59e0b')
                ];
            });

        return response()->json($leaves);
    }

    // Display the user's leave requests history
    public function myRequests()
    {
        $leaves = LeaveApplication::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pages.leaves.my-requests', compact('leaves'));
    }
}