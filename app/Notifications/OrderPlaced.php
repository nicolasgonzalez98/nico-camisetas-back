<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class OrderPlaced extends Notification
{
    use Queueable;
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order){
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Gracias por tu compra')
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Recibimos tu pedido #' . $this->order->id)
                    ->line('Total: $' . $this->order->total)
                    ->line('Te avisaremos cuando esté en camino.')
                    ->salutation('Gracias por comprar con nosotros.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
