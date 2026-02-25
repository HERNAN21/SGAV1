<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificacionResource extends JsonResource
{
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}