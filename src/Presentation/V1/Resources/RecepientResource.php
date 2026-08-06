<?php

namespace Notification\Presentation\V1\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin User
 */
#[OA\Schema(
    schema: 'RecepientResource',
    properties: [
        new OA\Property(
            property: 'id',
            description: 'The id of the recepient',
            type: 'int',
        ),
        new OA\Property(
            property: 'name',
            description: 'The name of the recepient',
            type: 'string',
        ),
    ],
)]
class RecepientResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
        ];
    }
}
