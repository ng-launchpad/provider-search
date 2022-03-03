<?php

namespace App\Http\Resources;

use App\Helper\Formatter;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id'         => $this->id,
            'label'      => $this->label,
            'type'       => $this->type,
            'address'    => [
                'line_1' => $this->address_line_1,
                'line_2' => $this->address_line_2,
                'city'   => $this->address_city,
                'county' => $this->address_county,
                'state'  => new StateResource($this->whenLoaded('state')),
                'zip'    => $this->address_zip,
            ],
            'phone'      => Formatter::phone($this->phone),
            'is_primary' => $this->whenPivotLoaded('location_provider', function () {
                return $this->pivot->is_primary;
            }),
        ];
    }
}
