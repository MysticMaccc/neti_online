<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendBillingStatementConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    protected $serialnumber;
    protected $company;
    protected $course;
    protected $trainingdate;

    /**
     * Create a new message instance.
     */
    public function __construct($serialnumber,$company,$trainingdate,$course)
    {
        //
        $this->serialnumber = $serialnumber;
        $this->company = $company;
        $this->trainingdate = $trainingdate;
        $this->course = $course;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('OESX: Billing statement received by client')
                    ->view('mails.send-billing-statement-confirmation')
                    ->with('serialnumber' , $this->serialnumber)
                    ->with('company' , $this->company)
                    ->with('trainingdate' , $this->trainingdate)
                    ->with('course' , $this->course);
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
