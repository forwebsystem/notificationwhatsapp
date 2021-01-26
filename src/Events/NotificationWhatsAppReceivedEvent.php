<?php

namespace ForWebSystem\NotificationWhatsApp\Events;

use ForWebSystem\NotificationWhatsApp\Model\NotificationWhatsAppReceived;
use ForWebSystem\NotificationWhatsApp\Model\WoowaMensagem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationWhatsAppReceivedEvent
{

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * @var NotificationWhatsAppReceived
     */
    public $mensagem = '';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NotificationWhatsAppReceived $notificationWhatsAppReceived)
    {
        $this->mensagem = $notificationWhatsAppReceived;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
