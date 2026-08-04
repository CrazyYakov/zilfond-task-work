<?php

namespace Notification\Presentation\V1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'recepientId' => new RecepientResource($this->recepient),
            'status' => $this->status->name,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}