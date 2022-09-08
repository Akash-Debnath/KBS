<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeleteArticleNotify extends Notification
{
    use Queueable;

    public $article;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($article, $user)
    {
        //
        $this->article = $article;
        $this->user    = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Article Deleted')
        ->greeting('Hello '.$this->user->first_name, 'User')
        ->line('One article has been delated')
        ->line('Article title : '.$this->article->en_title) //Send with post title
        ->line('Thank you for being with us!');
    }

    public function toDatabase($notifiable)
    {
        return [
           'type' => "Delete article",
           'sent_user_id' => $this->user->id,
           'article_id'=>$this->article->id,
           'article_title'=>$this->article->en_title,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
