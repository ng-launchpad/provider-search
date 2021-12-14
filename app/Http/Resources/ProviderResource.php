<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'id'                        => $this->id,
            'label'                     => $this->label,
            'npi'                       => $this->npi,
            'phone'                     => $this->phone,
            'degree'                    => $this->degree,
            'website'                   => $this->website,
            'gender'                    => $this->gender,
            'is_facility'               => (bool) $this->is_facility,
            'is_accepting_new_patients' => (bool) $this->is_accepting_new_patients,
            'network'                   => new NetworkResource($this->whenLoaded('network')),
            'locations'                 => LocationResource::collection($this->whenLoaded('locations')),
        ];
    }
}
