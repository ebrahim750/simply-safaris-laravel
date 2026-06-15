<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSubmission $contactSubmission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Submission - '.$this->contactSubmission->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-submitted',
            with: [
                'name' => $this->contactSubmission->name,
                'email' => $this->contactSubmission->email,
                'phone' => $this->contactSubmission->phone,
                'message' => $this->contactSubmission->message,
                'submittedAt' => $this->contactSubmission->created_at->format('F j, Y g:i A'),
            ],
        );
    }
}
