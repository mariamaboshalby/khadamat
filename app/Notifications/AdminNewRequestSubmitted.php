<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Request;
use App\Helpers\EncryptionHelper;

class AdminNewRequestSubmitted extends Notification
{
    use Queueable;

    protected $request;

    /**
     * Create a new notification instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
            'message' => 'طلب جديد مقدم: ' . $this->request->service->name,
            'request_id' => $this->request->id,
            'customer_name' => $this->request->user->name,
            'service_name' => $this->request->service->name,
            'created_at' => $this->request->created_at,
            'action_url' => route('admin.requests.show', EncryptionHelper::encryptId($this->request->id)),
            'icon' => 'fa-clipboard-list',
        ];
    }
}