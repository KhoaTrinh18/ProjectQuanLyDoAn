<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DuyetSuaDeTaiMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $deTai;
    public $ngay;
    public $noiDungSua;

    public function __construct($deTai, $ngay, $noiDungSua)
    {
        $this->deTai = $deTai;
        $this->ngay = $ngay;
        $this->noiDungSua = $noiDungSua;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Duyệt đề tài nhưng cần chỉnh sửa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.duyetSuaDeTai',
        );
    }

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
