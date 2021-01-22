<?php

namespace ForWebSystem\NotificationWhatsApp\Events;

use ForWebSystem\NotificationWhatsApp\Model\WoowaMensagem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationWhatsAppRecebidoEvent
{

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * @var WoowaMensagem
     */
    public $mensagem = '';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WoowaMensagem $notificationwhatsMensagem)
    {
        $this->mensagem = $notificationwhatsMensagem;
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
