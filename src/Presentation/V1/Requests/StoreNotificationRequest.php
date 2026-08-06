<?php

namespace Notification\Presentation\V1\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Notification\Infrastructure\Services\TransportService;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreNotificationRequest',
    properties: [
        new OA\Property(
            property: 'text',
            description: 'The text of the notification',
            type: 'string',
            example: 'This is a test notification',
        ),
        new OA\Property(
            property: 'recipientId',
            description: 'The ID of the recipient',
            type: 'integer',
            example: 1,
        ),
        new OA\Property(
            property: 'transport',
            description: 'The transport to use',
            type: 'string',
            example: 'Email',
        ),
    ],
    required: [
        'text',
        'recipientId',
        'transport',
    ]
)]
/**
 * @property-read string $text
 * @property-read int $recipientId
 * @property-read string $transport
 */
class StoreNotificationRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(TransportService $transportService): array
    {
        return [
            'text' => [
                'string',
                'required',
                'max:500',
            ],
            'recipientId' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'transport' => [
                'required',
                'string',
                static function (string $attribute, mixed $value, Closure $fail) use ($transportService) {
                    if (! $transportService->exists($value)) {
                        $fail('The selected transport is invalid.');
                    }
                },
            ],
        ];
    }
}
