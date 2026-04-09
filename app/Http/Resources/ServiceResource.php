<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->service_id,
            'name'         => $this->s_name,
            'description'  => $this->description,
            'price'        => $this->price,
            'city'         => $this->city,
            'location'     => $this->location,
            'avg_rating'   => $this->avg_rating,
            'review_count' => $this->review_count,
            'contact'      => [
                'phone' => $this->contact_phone,
                'email' => $this->contact_email,
            ],
            'category'     => $this->type?->service_name,
            'provider'     => $this->provider?->first_name . ' ' . $this->provider?->last_name,
            'media'        => $this->media->map(fn($m) => [
                'url'   => $m->file_path,
                'type'  => $m->file_type,
            ]),
            'details'      => $this->details->map(fn($d) => [
                'key'   => $d->detail_key,
                'value' => $d->detail_val,
            ]),
        ];
    }
}