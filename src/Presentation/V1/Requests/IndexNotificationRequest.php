<?php

namespace Notification\Presentation\V1\Requests;

use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Notification\Infrastructure\Services\TransportService;

/**
 * @property-read string $status
 * @property-read int $recepientId
 * @property-read string $transport
 */
class IndexNotificationRequest extends FormRequest
{
    public function rules(TransportService $service): array
    {
        return [
            'status' => [
                'string',
                Rule::enum(NotificationStatusEnum::class),
            ],
            'recepientId' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'transport' => [
                'string',
                static function (string $attribute, mixed $value, \Closure $fail) use ($service) {
                    if (! $service->exists($value)) {
                        $fail('The selected transport is invalid.');
                    }
                },
            ],
        ];
    }
}
