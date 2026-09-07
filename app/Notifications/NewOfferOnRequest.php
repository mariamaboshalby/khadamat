<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Helpers\EncryptionHelper;

class NewOfferOnRequest extends Notification
{
    use Queueable;

    public $request;
    public $proposal;

    public function __construct($request, $proposal)
    {
        $this->request = $request;
        $this->proposal = $proposal;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'request_id' => $this->request->id,
            'proposal_id' => $this->proposal->id,
            'technician_name' => $this->proposal->technician->user->name,
            'proposed_price' => $this->proposal->proposed_price,
            'message' => 'تم استلام عرض سعر جديد على طلبك',
            'action_url' => route('requests.show', EncryptionHelper::encryptId($this->request->id)),
            'icon' => 'fa-tag',
        ];
    }
}
