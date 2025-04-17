<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmed extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tu pedido fue confirmado')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu pedido #' . $this->order->id . ' fue confirmado.')
            ->line('En breve estará en camino. Total: $' . $this->order->total)
            ->salutation('¡Gracias por confiar en nosotros!');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

