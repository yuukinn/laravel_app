<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsEmail extends Mailable
{
    use Queueable, SerializesModels;

    // メールの内容に使用するプロパティを定義
    public $name;
    public $email;
    public $goalAmount;
    public $totalAmount;
    public const MAIL_SUBJECT = "目標金額へ迫っているお知らせ";
    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $goalAmount, $totalAmount)
    {
        //
        $this->name = $name;
        $this->email = $email;
        $this->totalAmount = $totalAmount;
        $this->goalAmount = $goalAmount;
    }

    public function build()
    {
        return $this->to($this->email)
            ->view('mail.news')
            ->subject(self::MAIL_SUBJECT)
            ->with([
                'name' => $this->name,
                'totalAmount' => $this->totalAmount,
                'goalAmount' => $this->goalAmount,
            ]);
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'News Email',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
