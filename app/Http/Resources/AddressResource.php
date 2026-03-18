<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Address
 */
class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'label' => $this->label,
            'formatted_address' => $this->formatted_address,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'area' => $this->area,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_default' => $this->is_default,
        ];
    }
}
