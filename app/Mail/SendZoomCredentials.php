<?php

namespace App\Mail;

use App\Models\tblcourseschedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendZoomCredentials extends Mailable
{
    use Queueable, SerializesModels;
    public $schedule;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
        $this->schedule = tblcourseschedule::where('batchno' , '=' , 'October Week 1 2023')->get();
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        foreach($this->schedule as $schedules)
        {
                // dump($schedules->course->coursecode);
                return $this->subject('OESX: Zoom Credentials')
                ->with('course' , $schedules->course->coursecode)
                ->view('mails.send-zoom-credentials');
        }
        
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
