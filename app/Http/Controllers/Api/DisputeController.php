<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Booking;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $disputes = Dispute::where('user_id', $request->user()->user_id)
            ->get()
            ->map(fn($d) => [
                'id'               => $d->dispute_id,
                'issue'            => $d->issue_description,
                'decision'         => $d->manager_decision,
                'booking_id'       => $d->booking_id,
            ]);

        return response()->json($disputes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id'        => 'required|exists:booking,booking_id',
            'issue_description' => 'required|string|max:2000',
        ]);

        // Make sure booking belongs to this user
        $booking = Booking::where('booking_id', $data['booking_id'])
            ->where('user_id', $request->user()->user_id)
            ->firstOrFail();

        $dispute = Dispute::create([
            'issue_description' => $data['issue_description'],
            'manager_decision'  => null,
            'user_id'           => $request->user()->user_id,
            'booking_id'        => $data['booking_id'],
        ]);

        return response()->json([
            'message' => 'Dispute submitted successfully',
            'dispute' => $dispute,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $dispute = Dispute::where('user_id', $request->user()->user_id)
            ->findOrFail($id);

        return response()->json($dispute);
    }
}