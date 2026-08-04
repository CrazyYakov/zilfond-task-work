<?php

namespace Notification\Presentation\V1\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Notification\Infrastructure\Services\TransportService;

/**
 * @property-read string $text
 * @property-read int $recepientId
 * @property-read string $transport
 */
class StoreNotificationRequest extends FormRequest
{
    public function rules(TransportService $transportService): array 
    {
        return [
            'text' => [
                'text',
                'required',
                'max:500',
            ],
            'recepientId' => [
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