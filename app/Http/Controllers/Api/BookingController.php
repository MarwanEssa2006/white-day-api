<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\BookingIncludes;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // GET /api/bookings — list my bookings
    public function index(Request $request)
    {
        $bookings = Booking::with(['services', 'customer'])
            ->where('user_id', $request->user()->user_id)
            ->orderBy('booking_id', 'desc')
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    // GET /api/bookings/{id} — single booking
    public function show(Request $request, $id)
    {
        $booking = Booking::with(['services', 'customer'])
            ->where('user_id', $request->user()->user_id)
            ->findOrFail($id);

        return new BookingResource($booking);
    }

    // POST /api/bookings — create booking
    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_date'  => 'required|date',
            'delivery_date' => 'nullable|date|after:booking_date',
            'notes'         => 'nullable|string',
            'services'      => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:service,service_id',
            'services.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total
            $total = 0;
            $lines = [];

            foreach ($data['services'] as $item) {
                $service = Service::findOrFail($item['service_id']);
                $lineTotal = $service->price * $item['quantity'];
                $total += $lineTotal;

                $lines[] = [
                    'service_id' => $service->service_id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $service->price,
                ];
            }

            // Create booking
            $booking = Booking::create([
                'booking_date'  => $data['booking_date'],
                'delivery_date' => $data['delivery_date'] ?? null,
                'notes'         => $data['notes'] ?? null,
                'total_amount'  => $total,
                'status'        => 'pending',
                'user_id'       => $request->user()->user_id,
            ]);

            // Attach services
            foreach ($lines as $line) {
                BookingIncludes::create([
                    'booking_id' => $booking->booking_id,
                    'service_id' => $line['service_id'],
                    'quantity'   => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                ]);
            }

            DB::commit();

            return new BookingResource($booking->load('services'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Booking failed: ' . $e->getMessage()], 500);
        }
    }

    // PUT /api/bookings/{id}/cancel — cancel a booking
    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->user_id)
                          ->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'message' => 'Only pending or confirmed bookings can be cancelled.'
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}