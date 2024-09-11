<?php

namespace App\Listeners;
use App\Events\MessagePosted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Pusher\Pusher;

class SendMessageListener
{
    /**
     * Create the event listener.
     */
    private $pusher;

    public function __construct()
    {
        $config = Config::get('broadcasting.connections.pusher');

        $options = [
            'cluster' => $config['options']['cluster'],
            'encrypted' => $config['options']['encrypted']
        ];

        $this->pusher = new Pusher(
            $config['key'],
            $config['secret'],
            $config['app_id'],
            $options
        );
    }

    /**
     * Handle the event.
     */
    public function handle(MessagePosted $event): void
    {
        
        // dd(1231231);
        // $data = $event->getData();
        // $data['user'] = 'congvinh';
        // $this->pusher->trigger('private-chat', 'send-message', $event);
    }
}
