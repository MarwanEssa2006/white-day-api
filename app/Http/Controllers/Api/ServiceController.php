<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // GET /api/services  (public)
    public function index(Request $request)
    {
        $query = Service::with(['type', 'provider', 'media', 'details']);

        // Filter by category
        if ($request->has('category')) {
            $query->where('t_id', $request->category);
        }

        // Filter by city
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        // Filter by max price
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('s_name', 'like', '%' . $request->search . '%');
        }

        // Sort by rating or price
        $sort = $request->get('sort', 'avg_rating');
        $query->orderBy($sort, 'desc');

        return ServiceResource::collection($query->paginate(10));
    }

    // GET /api/services/{id}  (public)
    public function show($id)
    {
        $service = Service::with(['type', 'provider', 'media', 'details', 'reviews'])
                          ->findOrFail($id);

        return new ServiceResource($service);
    }

    // POST /api/services  (provider only)
    public function store(Request $request)
    {
        $data = $request->validate([
            's_name'        => 'required|string|max:150',
            'description'   => 'nullable|string',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email',
            'price'         => 'required|numeric|min:0',
            'city'          => 'nullable|string|max:50',
            'location'      => 'nullable|string|max:100',
            't_id'          => 'required|exists:service_type,t_id',
        ]);

        $data['user_id'] = $request->user()->user_id;

        $service = Service::create($data);

        return new ServiceResource($service->load(['type', 'provider']));
    }

    // PUT /api/services/{id}  (provider only — own services)
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        if ($service->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $service->update($request->validate([
            's_name'        => 'sometimes|string|max:150',
            'description'   => 'nullable|string',
            'contact_phone' => 'sometimes|string|max:20',
            'contact_email' => 'nullable|email',
            'price'         => 'sometimes|numeric|min:0',
            'city'          => 'nullable|string|max:50',
            'location'      => 'nullable|string|max:100',
        ]));

        return new ServiceResource($service->load(['type', 'provider']));
    }

    // DELETE /api/services/{id}  (provider only — own services)
    public function destroy(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        if ($service->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }
}