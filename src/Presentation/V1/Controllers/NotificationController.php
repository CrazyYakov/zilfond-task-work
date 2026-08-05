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

class NotificationController
{
    public function __construct(
        public CreateNotificationAction $createNotificationAction,
        private IndexNotificationAction $indexNotficationAction,
    ) {}

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

    public function show(Notification $notification): Responsable
    {
        return new NotificationResource($notification);
    }

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
