<?php

namespace Notification\Presentation\V1\Resources;

use App\Models\Notification;
use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin Notification
 */
#[OA\Schema(
    schema: 'NotificationResource',
    properties: [
        new OA\Property(
            property: 'id',
            description: 'The id of the notification',
            type: 'int',
        ),
        new OA\Property(
            property: 'text',
            description: 'The text of the notification',
            type: 'string',
        ),
        new OA\Property(
            property: 'recipient',
            description: 'The recipient of the notification',
            ref: RecepientResource::class,
        ),
        new OA\Property(
            property: 'status',
            description: 'The status of the notification',
            enum: NotificationStatusEnum::class,
        ),
        new OA\Property(
            property: 'createdAt',
            description: 'The created at of the notification',
            type: 'string',
            format: 'date-time',
        ),
        new OA\Property(
            property: 'updatedAt',
            description: 'The updated at of the notification',
            type: 'string',
            format: 'date-time',
        ),
    ]
)]
class NotificationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'recipient' => new RecepientResource($this->recipient),
            'status' => $this->status->value,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
