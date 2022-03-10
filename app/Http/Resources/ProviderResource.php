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
            'type'                      => $this->type,
            'npi'                       => $this->npi,
            'degree'                    => $this->degree,
            'website'                   => $this->website,
            'gender'                    => ucwords(strtolower($this->gender)),
            'is_facility'               => $this->is_facility,
            'is_accepting_new_patients' => $this->is_accepting_new_patients,
            'network'                   => new NetworkResource($this->whenLoaded('network')),

            'locations'                 => LocationResource::collection($this->whenLoaded('locations', collect(), collect())->unique('hash')),
            'languages'                 => LanguageResource::collection($this->whenLoaded('languages', collect(), collect())->unique()),
            'specialities'              => SpecialityResource::collection($this->whenLoaded('specialities', collect(), collect())->unique()),
            'hospitals'                 => HospitalResource::collection($this->whenLoaded('hospitals', collect(), collect())->unique()),

            'people'                    => ProviderResource::collection($this->people),
        ];
    }
}
