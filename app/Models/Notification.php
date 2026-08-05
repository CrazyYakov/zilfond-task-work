<?php

namespace App\Models;

use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $text
 * @property string $name
 * @property NotificationStatusEnum $status
 * @property int $recipient_id
 * @property string $transport
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * 
 * @property-read User $recipient
 */
class Notification extends Model
{
    use HasFactory;
    protected $casts = [
        'status' => NotificationStatusEnum::class,
    ];

    protected $attributes = [
        'status' => NotificationStatusEnum::PROGRESS,
    ];

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
