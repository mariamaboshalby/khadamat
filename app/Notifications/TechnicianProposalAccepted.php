<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RequestProposal;
use App\Helpers\EncryptionHelper;

class TechnicianProposalAccepted extends Notification
{
    use Queueable;

    protected $proposal;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequestProposal $proposal)
    {
        $this->proposal = $proposal;
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
            'message' => 'تم قبول عرض السعر الخاص بك لطلب: ' . $this->proposal->request->service->name,
            'request_id' => $this->proposal->request->id,
            'service_name' => $this->proposal->request->service->name,
            'proposal_id' => $this->proposal->id,
            'created_at' => $this->proposal->created_at,
            'action_url' => route('technician.repair-requests.show', EncryptionHelper::encryptId($this->proposal->request->id)),
            'icon' => 'fa-check-circle',
        ];
    }
}