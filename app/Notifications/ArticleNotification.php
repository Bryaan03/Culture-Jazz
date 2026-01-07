<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleNotification extends Notification
{
    use Queueable;

    public function __construct(public $article)
    {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'article_id' => $this->article->id,
            'titre' => $this->article->titre,
            'auteur' => $this->article->editeur->name,
        ];
    }
}
