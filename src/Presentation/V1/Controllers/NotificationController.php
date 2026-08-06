<?php

namespace Notification\Presentation\V1\Controllers;

use App\Models\Notification;
use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;
use Notificaiton\Application\Dto\CreateNotificationDto;
use Notification\Application\Actions\CreateNotificationAction;
use Notification\Application\Actions\IndexNotificationAction;
use Notification\Application\Dto\IndexNotificationDto;
use Notification\Presentation\V1\Requests\IndexNotificationRequest;
use Notification\Presentation\V1\Requests\StoreNotificationRequest;
use Notification\Presentation\V1\Resources\NotificationResource;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Notification',
    description: 'Notification API'
)]
class NotificationController
{
    public function __construct(
        public CreateNotificationAction $createNotificationAction,
        private IndexNotificationAction $indexNotficationAction,
    ) {}

    #[
        OA\Post(
            path: '/api/v1/notifications',
            tags: ['Notification'],
            description: 'Create a new notification',

        ),
        OA\RequestBody(
            content: new OA\JsonContent(
                ref: StoreNotificationRequest::class,
            )
        ),
        OA\Response(
            response: 201,
            description: 'Notification created successfully',
            content: new OA\JsonContent(
                ref: NotificationResource::class
            ),
        )
    ]
    public function store(StoreNotificationRequest $request): Responsable
    {
        $dto = new CreateNotificationDto(
            text: $request->text,
            recipientId: $request->recipientId,
            transport: $request->transport,
        );

        $notification = $this->createNotificationAction->execute($dto);

        return new NotificationResource($notification);
    }

    #[
        OA\Get(
            path: '/api/v1/notifications/{notification}',
            tags: ['Notification'],
            description: 'Find notificaiton',
            parameters: [
                new OA\Parameter(
                    name: 'notification',
                    description: 'Notification ID',
                    required: true,
                    in: 'path',
                    schema: new OA\Schema(type: 'integer'),
                ),
            ]
        ),
        OA\Response(
            response: 200,
            description: 'Notification found',
            content: new OA\JsonContent(
                ref: NotificationResource::class
            ),
        )
    ]
    public function show(Notification $notification): Responsable
    {
        return new NotificationResource($notification);
    }

    #[
        OA\Get(
            path: '/api/v1/notifications',
            tags: ['Notification'],
            description: 'Paginate notifications',
            parameters: [
                new OA\Parameter(
                    name: 'recipientId',
                    description: 'recipient id',
                    required: true,
                    in: 'query',
                    schema: new OA\Schema(type: 'integer'),
                ),
                new OA\Parameter(
                    name: 'status',
                    description: 'status',
                    required: false,
                    in: 'query',
                    schema: new OA\Schema(type: 'string', enum: NotificationStatusEnum::class),
                ),
                new OA\Parameter(
                    name: 'transport',
                    description: 'transport',
                    required: false,
                    in: 'query',
                    schema: new OA\Schema(type: 'string'),
                ),
            ]
        ),
        OA\Response(
            response: 200,
            description: 'Notification found',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: NotificationResource::class)
                    ),
                ]
            ),
        )
    ]
    public function index(IndexNotificationRequest $request): Jsonable
    {
        $dto = new IndexNotificationDto(
            recipientId: $request->recipientId,
            status: $request->enum('status', NotificationStatusEnum::class),
            transport: $request->transport,
        );

        $pagination = $this->indexNotficationAction->execute($dto);

        $pagination->through(fn (Notification $notification) => new NotificationResource($notification));

        return $pagination;
    }
}
