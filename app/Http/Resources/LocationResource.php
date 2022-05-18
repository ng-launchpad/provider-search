<?php

namespace App\Http\Resources;

use App\Helper\Formatter;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $address = [
            'label'  => $this->label,
            'line_1' => $this->address_line_1,
            'line_2' => $this->address_line_2,
            'city'   => $this->address_city,
            'county' => $this->address_county,
            'state'  => new StateResource($this->whenLoaded('state')),
            'zip'    => Str::limit($this->address_zip, 5, ''),
        ];

        $addressString = implode(', ', array_filter([
            $this->label,
            $this->address_line_1,
            $this->address_line_2,
            $this->address_city,
            $this->address_county,
            $this->whenLoaded('state')->label ?? null,
            Str::limit($this->address_zip, 5, ''),
        ]));

        $addressMap = 'https://maps.google.com/?q=' . urlencode($addressString);

        $addressHelpers = [
            'string' => $addressString,
            'map'    => $addressMap,
        ];

        return [
            'id'         => $this->id,
            'label'      => $this->label,
            'type'       => $this->type,
            'address'    => array_merge(
                $address,
                $addressHelpers
            ),
            'phone'      => Formatter::phone($this->phone),
            'is_primary' => $this->whenPivotLoaded('location_provider', function () {
                return $this->pivot->is_primary;
            }),
        ];
    }
}
