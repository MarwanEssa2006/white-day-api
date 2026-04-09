<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($serviceId)
    {
        $reviews = Review::with('user')
            ->where('service_id', $serviceId)
            ->get()
            ->map(fn($r) => [
                'id'        => $r->review_id,
                'stars'     => $r->rate_star,
                'comment'   => $r->comment,
                'reviewer'  => $r->user?->first_name . ' ' . $r->user?->last_name,
            ]);

        return response()->json($reviews);
    }

    public function store(Request $request, $serviceId)
    {
        $data = $request->validate([
            'rate_star' => 'required|integer|min:1|max:5',
            'comment'   => 'nullable|string|max:1000',
        ]);

        // Check user has a completed booking that includes this service
        $hasBooking = Booking::where('user_id', $request->user()->user_id)
            ->where('status', 'completed')
            ->whereHas('services', fn($q) => $q->where('service.service_id', $serviceId))
            ->exists();

        if (!$hasBooking) {
            return response()->json([
                'message' => 'You can only review services from completed bookings.'
            ], 403);
        }

        $review = Review::create([
            'rate_star'  => $data['rate_star'],
            'comment'    => $data['comment'] ?? null,
            'user_id'    => $request->user()->user_id,
            'service_id' => $serviceId,
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review'  => $review,
        ], 201);
    }
}