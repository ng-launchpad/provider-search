<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SyncFailureNotification extends Notification
{
    use Queueable;

    private \Throwable $exception;
    private array $log;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\Throwable $exception, array $log)
    {
        $this->exception = $exception;
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

        //  indenting causes code block/markdown
        $type    = get_class($this->exception);
        $message = $this->exception->getMessage();
        $code    = $this->exception->getCode();
        $file    = $this->exception->getFile();
        $line    = $this->exception->getLine();

        $table = <<<HEREDOC
<table style="width: 100%; border: 1px solid #ccc; margin-bottom:20px;padding:10px;">
    <tr>
        <td style="font-weight: bold; width: 100px;">Type</td>
        <td>$type</td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 100px;">Message</td>
        <td>$message</td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 100px;">Code</td>
        <td>$code</td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 100px;">File</td>
        <td>$file</td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 100px;">Line</td>
        <td>$line</td>
    </tr>
</table>
HEREDOC;

        $log = sprintf(
            '<code style="display: block; width: 100%%; padding: 10px; white-space: pre-wrap;">%s</code>',
            implode(PHP_EOL, array_map('strip_tags', $this->log))
        );

        return (new MailMessage)
            ->error()
            //  @todo (Pablo 2022-01-26) - customise sender
            ->subject('Sync Failed')
            ->greeting('Sync Failed')
            ->line('This email is to advise you that the most recent sync failed.')
            ->line('The error caught was:')
            ->line(new HtmlString($table))
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
