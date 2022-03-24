<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NetworkResource extends JsonResource
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
            'id'              => $this->id,
            'label'           => $this->label,
            'search_label'    => $this->search_label,
            'search_sublabel' => $this->search_sublabel,
            'network_label'   => $this->network_label,
            'browse_label'    => $this->browse_label,
            'searching_label' => $this->searching_label,
            'legal'           => [
                'home'     => $this->legal_home,
                'search'   => $this->legal_search,
                'browse'   => $this->legal_browse,
                'provider' => $this->legal_provider,
                'facility' => $this->legal_facility,
            ],
        ];
    }
}
