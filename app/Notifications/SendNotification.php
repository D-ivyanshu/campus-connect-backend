<?php

namespace App\Notifications;

use App\Http\Resources\UserResource;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SendNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $title = "";
    public $type;
    public $user;
    public $is_following = null;
    public $post = [];
    public function __construct($type, $title, $post, $user, $is_following)
    {   
        $this->type = $type;
        $this->title = $title;
        $this->post = $post;
        $this->user = $user;
        $this->is_following = $is_following;
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

    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     $notification = [
    //         'data' => [
    //             'id' => $notifiable->id,
    //             'title' => $this->title,
    //             'post_id' => $this->post->id
    //         ]
    //     ];
    //     return new BroadcastMessage($notification);
    // }


    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {   
        return [
            'type' => $this->type,
            'title' => $this->title,
            'user' => $this->user,
            'post_id' => ($this->post) ? $this->post->id : null,
            'is_following' => $this->is_following
        ];
    }
}
