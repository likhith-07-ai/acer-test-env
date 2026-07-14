<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $subject;
    public $contactMessage;
    public $phone;
    public $organization;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $subject, $message, $phone = null, $organization = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->contactMessage = $message;
        $this->phone = $phone;
        $this->organization = $organization;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // For Gmail SMTP, from address should match the username
        // Use username if available, otherwise use configured from address
        $fromAddress = config('mail.mailers.smtp.username') 
            ?: config('mail.from.address', 'noreply@acerratings.com');
        $fromName = config('mail.from.name', 'ACER Ratings');
        
        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: 'New Contact Form Submission: ' . $this->subject,
            replyTo: [
                new Address($this->email, $this->name),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form',
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
