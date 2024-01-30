<?php

namespace App\Http\Livewire\Landing;

use App\Models\tblinquirytype;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInquiry; // Make sure to adjust the namespace as needed
use App\Models\tblemailinquiry;
use Carbon\Carbon;

class Contact extends Component
{


    public $name;
    public $email;
    public $mobile;
    public $company;
    public $selectedInquiryType;
    public $message;
    protected $listeners = ['danielsweetalert'];

    public function addinquiry()
    {
        
        $queryadd = new tblemailinquiry;
        $queryadd->hash_id = NULL;
        $queryadd->name = $this->name;
        $queryadd->email = $this->email;
        $queryadd->mobile = $this->mobile;
        $queryadd->company = $this->company;
        $queryadd->inquirytypeid= $this->selectedInquiryType;
        $queryadd->inquiry_text = $this->message;
        $queryadd->is_answered = 0;
        $queryadd->deleted_by = 0;
        $queryadd->faqid = 0;
        $queryadd->save();

        $lastInsertedemailinquiryid = tblemailinquiry::latest('emailinquiryid')->value('emailinquiryid');

        tblemailinquiry::where('emailinquiryid', $lastInsertedemailinquiryid)
        ->update(['hash_id' => hash('sha256', $lastInsertedemailinquiryid)]);


          // Send the email
   
            $name = $this->name;
            $email = $this->email;
            $mobile = $this->mobile;
            $datenow = Carbon::now('Asia/Manila');
            $inquirytype = tblinquirytype::find($this->selectedInquiryType);
            $inquirytypecontent = $inquirytype->inquirytype;
            $company = $this->company;
            $inquiry_text = $this->message;
  
            // Mail::to('louise.mejico@neti.com.ph') // Replace with the recipient's email address
            //     ->send(new SendInquiry($name,$email,$mobile,$datenow,$company,$inquiry_text,$inquirytypecontent));

            $recipientEmail = 'inquiry@neti.com.ph';
            $ccEmails = ['daniel.narciso@neti.com.ph']; // Replace with the CC recipient's email addresses
            $bccEmails = ['louise.mejico@neti.com.ph']; // Replace with the BCC recipient's email addresses

        Mail::to($recipientEmail)
        ->cc($ccEmails)
        ->bcc($bccEmails)
        ->send(new SendInquiry($name, $email, $mobile, $datenow, $company, $inquiry_text, $inquirytypecontent));

        // Add the success message or any desired behavior here
        $this->dispatchBrowserEvent('danielsweetalert', [
            'title' => 'Thank you for reaching out to us!🌟 We will get back to you soon',
            'position' => 'middle',
            'icon' => 'success',
            'confirmbtn' => true

    
           ]);


    return redirect()->route('contact');

    }

    public function render()
    {
        $inquirytype = tblinquirytype::all(); 


        return view('livewire.landing.contact', [
            'inquirytype' => $inquirytype
        ])->layout('layouts.base');
    }

}
