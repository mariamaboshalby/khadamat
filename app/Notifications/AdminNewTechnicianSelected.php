<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Technician;

class AdminNewTechnicianSelected extends Notification
{
    use Queueable;

    protected $technician;

    /**
     * Create a new notification instance.
     */
    public function __construct(Technician $technician)
    {
        $this->technician = $technician;
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
            'message' => 'تم اختيار فني جديد: ' . $this->technician->user->name,
            'technician_id' => $this->technician->id,
            'technician_name' => $this->technician->user->name,
            'specialization' => $this->technician->specialization->name ?? 'غير محدد',
            'created_at' => $this->technician->created_at,
        ];
    }
}