<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->booking_id,
            'status'        => $this->status,
            'booking_date'  => $this->booking_date,
            'delivery_date' => $this->delivery_date,
            'total_amount'  => $this->total_amount,
            'notes'         => $this->notes,
            'customer'      => $this->customer?->first_name . ' ' . $this->customer?->last_name,
            'services'      => $this->services->map(fn($s) => [
                'id'         => $s->service_id,
                'name'       => $s->s_name,
                'quantity'   => $s->pivot->quantity,
                'unit_price' => $s->pivot->unit_price,
            ]),
        ];
    }
}