<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEnrollmentConfirmationNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $course;
    public $trainingdate;
    public $enroledid;

    /**
     * Create a new message instance.
     */
    public function __construct($name,$course,$trainingdate,$enroledid)
    {
        //
        $this->name = $name;
        $this->course = $course;
        $this->trainingdate = $trainingdate;
        $this->enroledid = $enroledid;
    }

    public function build()
    {
        return $this->subject('OESX: Enrollment Confirmation')
                    ->with('name' , $this->name)
                    ->with('course' , $this->course)
                    ->with('trainingdate' , $this->trainingdate)
                    ->with('enroledid' , $this->enroledid)
                    ->view('mails.send-enrollment-confirmation-notification');
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
