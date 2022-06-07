<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SyncSuccessNotification extends Notification
{
    use Queueable;

    private array $log;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $log)
    {
        $this->log = $log;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $log = sprintf(
            '<pre style="display: block; width: 100%%; padding: 10px; color: #fff; background: #333;"><code>%s</code></pre>',
            implode(PHP_EOL, array_map('strip_tags', $this->log))
        );

        return (new MailMessage)
            ->subject('Sync Success')
            ->greeting('Sync Success')
            ->line('This email is to advise you that the most recent sync completed successfully.')
            ->line('The following logs were collected.')
            ->line(new HtmlString($log));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
