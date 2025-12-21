<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WarehouseItem;

class AdminLowStockAlert extends Notification
{
    use Queueable;

    protected $item;

    /**
     * Create a new notification instance.
     */
    public function __construct(WarehouseItem $item)
    {
        $this->item = $item;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'الكمية منخفضة في المخزن: ' . $this->item->name,
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'current_quantity' => $this->item->quantity,
            'min_quantity' => $this->item->min_quantity,
            'created_at' => now(),
        ];
    }
}