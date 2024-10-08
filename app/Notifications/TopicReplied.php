<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public Reply $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        // 注入回复实体，方便 toDatabase 方法中的使用
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        // 开启通知的频道，这里是数据库
        return ['database','mail'];
    }


    /**
     * 数据库存储通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        $topic = $this->reply->topic;
        $link = $topic->link(['#reply' . $this->reply->id]);

        // 存入数据库里的数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title
        ];
    }
    /**
     * 收到新回复后的邮件通知
     */
    public function toMail($notifiable):MailMessage
    {
        $url=$this->reply->topic->link(['#reply'.$this->reply->id]);

        return (new MailMessage())
        ->line("你的话题有新回复")
        ->action('查看回复',$url);

    }
}
