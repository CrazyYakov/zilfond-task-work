<?php

namespace Notification\Presentation\V1\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Notification\Infrastructure\Services\TransportService;

/**
 * @property-read string $text
 * @property-read int $recipientId
 * @property-read string $transport
 */
class StoreNotificationRequest extends FormRequest
{
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
