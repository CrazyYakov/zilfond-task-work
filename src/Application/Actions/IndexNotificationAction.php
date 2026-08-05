<?php

namespace Notification\Application\Actions;

use App\Models\Notification;
use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Notification\Application\Dto\IndexNotificationDto;

class IndexNotificationAction
{
    /**
     * @return Paginator<int, Notification>
     */
    public function execute(IndexNotificationDto $dto): Paginator
    {
        return Notification::query()
            ->where('recipient_id', $dto->recipientId)
            ->when($dto->status, fn (Builder $query, NotificationStatusEnum $status) => $query->where('status', $status->value))
            ->when($dto->transport, fn (Builder $query, string $transport) => $query->where('transport', $transport))
            ->simplePaginate();
    }
}
