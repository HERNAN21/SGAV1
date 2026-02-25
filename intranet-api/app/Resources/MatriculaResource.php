<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatriculaResource extends JsonResource
{
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}