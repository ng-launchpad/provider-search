<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LegalResource extends JsonResource
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
            'home'     => $this->home,
            'search'   => $this->search,
            'browse'   => $this->browse,
            'provider' => $this->provider,
            'facility' => $this->facility,
        ];
    }
}
