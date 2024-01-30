<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use Livewire\Component;
use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;


class AGenerateUrlCertificateComponent extends Component
{

    public $hash_id;

    public function mount($hash_id)
    {
        $this->hash_id = base64_decode($hash_id);

        $this->view_qrcode($hash_id);
    }

    public function view_qrcode()
    {
        $enroledid = $this->hash_id;

        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $crew = tblenroled::find($enroledid);

        $schedule = tblcourseschedule::find($crew->scheduleid);
        $course_type = $schedule->course->type->coursetypeid;

        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        $formattedStartDate = $trainingStartDate->format('F j');
        $formattedEndDate = $trainingEndDate->format('j, Y');

        $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;

        $dateissue = date("dS") . " day of " . date("F") . " " . date("Y");


        $templatePath = storage_path('app/public/uploads/') . '/' . $schedule->course->certificatepath;

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Generated Certificates');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';

        $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)->where('enroledid', $crew->enroledid)->where('traineeid', $crew->traineeid)->first();

        $pageCount = $pdf->setSourceFile($templatePath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $pageWidth = 210; // A4 width in points
            $pageHeight = 297; // A4 height in points

            // Add a page
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
            // Set content from existing PDF

            // Import the first page of the existing PDF

            $templateId = $pdf->importPage(1);
            $pdf->useTemplate($templateId);
            if (Auth::guard('trainee')->check() && Auth::guard('trainee')->user()->u_type == 2) {
                if ($course_type != "2") {
                    $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
                    //NMC Certificate Paper
                    $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                    $pdf->useImportedPage($templateId, 0, 0, 210);
                } else {
                    $imagePath = storage_path("app/public/uploads/certificatetemplate/Upgrading Certificate Paper.jpg");
                    //Upgrading Certificate Paper
                    $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                    $pdf->useImportedPage($templateId, 0, 0, 210);
                }
            }

            // Set additional content


            $pdf->SetFont($arialblack, '', 11); // Set the font
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetXY(90, 15);
            $control_number = $cert_history->controlnumber;
            $pdf->Cell(70, 0, $control_number, 0, 1, 'R', 0, '', 0);
            $pdf->SetTextColor(0, 0, 0);
            if ($cert_history) {
                $cert_num = $cert_history->certificatenumber;
            } else {
                switch (strlen($schedule->course->lastcertificatenumber)) {
                    case '1':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-000' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '2':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-00' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '3':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-0' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '4':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-' . $schedule->course->lastcertificatenumber++;
                        break;
                }
            }

            //CERTIFICATE ALIGNMENT
            $certAlignment = explode(',', $schedule->course->certificatenumberalignment);
            switch ($schedule->course->certfontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($certnumfont, $schedule->course->certfontstyle->fontstylevalue, $schedule->course->certfontsizeid);
            $pdf->SetXY($certAlignment[0], $certAlignment[1]);

            if ($cert_history) {
                $cert_num = $cert_history->certificatenumber;
            } else {
                switch (strlen($schedule->course->lastcertificatenumber)) {
                    case '1':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-000' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '2':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-00' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '3':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-0' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '4':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-' . $schedule->course->lastcertificatenumber++;
                        break;
                }
            }

            $pdf->Cell(70, 0, $cert_num, 0, 1, 'R', 0, '', 0);

            //REGISTRATION ALIGNMENT
            $regAlignment = explode(',', $schedule->course->registrationnumberalignment);

            $pdf->SetFont($certnumfont, $schedule->course->certfontstyle->fontstylevalue, $schedule->course->certfontsizeid);
            $pdf->SetXY($regAlignment[0], $regAlignment[1]);

            if ($cert_history) {
                $reg_number = $cert_history->registrationnumber;
                if ($crew->cln_id) {
                    $cln = $crew->cln->cln_type;
                    $cert_history->cln_type = $cln;
                    $cert_history->save();
                    $reg_num = $cln . ' - ' . $reg_number;
                } else {
                    $reg_num = $reg_number;
                }
            }

            $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

            //NAME ALIGNMENT
            $nameAlignment = explode(',', $schedule->course->namealignment);
            switch ($schedule->course->crewnamefontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($arialblack, $schedule->course->crewnamefontstyle->fontstylevalue,  $schedule->course->crewnamefontsizeid);
            $pdf->SetXY($nameAlignment[0], $nameAlignment[1]);
            // $pdf->Cell(210, 0, utf8_decode($crew->trainee->certificate_name()), 0, 1, 'C', 0, '', 0);
            $pdf->Cell(210, 0, $crew->trainee->certificate_name(), 0, 1, 'C', 0, '', 0);

            //BIRTHDAY ALIGNMENT
            $birthAlignment = explode(',', $schedule->course->birthdayalignment);
            switch ($schedule->course->birthdayfontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($certnumfont, $schedule->course->birthdayfontstyle->fontstylevalue,  $schedule->course->birthdayfontsizeid);
            $dob = $crew->trainee->birthday;
            $dobCarbon = Carbon::parse($dob)->format('F d, Y');
            $new_format_dob = "(DOB : " . $dobCarbon . ")";
            // Calculate the width of the name text
            $pdf->SetXY($birthAlignment[0], $birthAlignment[1]);
            if ($course_type != 1) {
                $pdf->Cell(210, 0, $new_format_dob, 0, 1, 'C', 0, '', 0);
            }

            //PICTURE ALIGNMENT
            $pictureAlignment = explode(',', $schedule->course->picturealignment);
            $pdf->SetFont('times', 'BI', 25);
            $pdf->SetXY($pictureAlignment[0], $pictureAlignment[1]);
            $pdf->setJPEGQuality(100);

            if ($crew->trainee->imagepath) {
                $imagePath = public_path("/storage/uploads/traineepic/" . $crew->trainee->imagepath);
            } else {
                $imagePath = public_path("assets/images/oesximg/noimageavailable.jpg");
            }
            $pdf->Image($imagePath, $pictureAlignment[0], $pictureAlignment[1], 29.5, 28, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);


            $qrcodeAlignment = explode(',', $schedule->course->qrcodealignment);
            $qrcode = base64_encode($crew->enroledid);

            
            $style = array(
                'border' => 0,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, //array(255,255,255)
                'module_width' => 1, // width of a single module in points
                'module_height' => 1 // height of a single module in points
            );
            $pdf->write2DBarcode(route('qr.code', ['hash_id' => $qrcode]), 'QRCODE,L', $qrcodeAlignment[0], $qrcodeAlignment[1], 18, 16, $style, 'N');
            //REMARKS ALIGNMENT
            switch ($schedule->course->remarksfontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($certnumfont, $schedule->course->remarksfontstyle->fontstylevalue, $schedule->course->remarksfontsizeid);
            $remarksAlignment = explode(',', $schedule->course->remarksalignment);
            $remarks = $schedule->course->certificateremarks;
            $certificateremarks = str_replace("trainingdate",  $trainingdateFormatted, $remarks);
            $certificateremarksmain = str_replace("practicaldate", $trainingdateFormatted, str_replace("dateissued", $dateissue, $certificateremarks));
            if ($course_type != 1) {
                $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
            } else {
                $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            }
        }
        $pdf->Output();
    }


    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-url-certificate-component');
    }
}
