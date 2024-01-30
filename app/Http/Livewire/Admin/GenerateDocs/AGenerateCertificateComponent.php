<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tbllastregistrationnumber;
use App\Models\tbltrainingcertserialnumber;
use App\Models\tblremedial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AGenerateCertificateComponent extends Component
{
    public $training_id;
    public $schedule;
    public $course_id;
    public $enroledid;

    public function viewPdf($training_id)
    {
        $this->enrolled_crews($training_id);
    }

    public function enrolled_crews($training_id)
    {

        $this->training_id = $training_id;
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $schedule = tblcourseschedule::find($this->training_id);

        $crews = tblenroled::where('scheduleid', $this->training_id)->where('pendingid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();
        // $crews = tblenroled::where('scheduleid', $this->training_id)->where('isAttending', 1)->where('attendance_status', 0)->where('passid', 1)
        //     ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
        //     ->orderBy('IsRemedial', 'desc')
        //     ->orderBy('tbltraineeaccount.f_name', 'asc')
        //     ->get();




        $course_type = $schedule->course->type->coursetypeid;

        $last_number_reg = tbllastregistrationnumber::find(1);
        $last_serialnumber = tbltrainingcertserialnumber::find(1);

        //date
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        $formattedStartDate = $trainingStartDate->format('F j');
        $formattedEndDate = $trainingEndDate->format('j, Y');

        $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;

        $dateissue = date("dS", $trainingEndDate->timestamp) . " day of " . date("F", $trainingEndDate->timestamp) . " " . date("Y", $trainingEndDate->timestamp);


        $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepath);

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Certificates');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';


        foreach ($crews as $crew) {
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
                if ($crew->trainee->company->companyid == 1 || $crew->trainee->company->companyid == 3 || $crew->trainee->company->companyid == 89 || $crew->trainee->company->companyid == 115 || $crew->trainee->company->companyid == 262) {
                    if ($course_type == "1") {
                    } else if ($course_type != "2") {
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

                //RED COLOR SERIAL
                $pdf->SetFont($arialblack, 'B', 11);
                $pdf->SetXY(170, 15);
                $pdf->SetTextColor(255, 0, 0);

                if ($cert_history) {
                    $cert_serial = $cert_history->controlnumber;
                } else {
                    $cert_serial = '';
                }

                if ($crew->course->type->coursetypeid == 3 || $crew->course->type->coursetypeid == 4) {
                    $pdf->Cell(0, 0, $cert_serial, 0, 1, 'L', 0, '', 0);
                }

                $pdf->SetTextColor(0, 0, 0);

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
                        $cert_history->controlnumber = $crew->controlnumber;
                        $cert_history->save();
                        $reg_num = $reg_number;
                        $reg_num_format = $cln . ' - ' .  $reg_num;
                    } else {
                        $reg_num = $reg_number;
                        $reg_num_format =  $reg_num;
                    }
                } else {
                    $cln = $crew->cln_id ? $crew->cln->cln_type : '';
                    $reg_cert_new = $last_number_reg->lastregistrationnumber++;
                    // $reg_num = $cln . ' - ' . $reg_cert_new;

                    switch (strlen($reg_cert_new)) {
                        case '1':
                            $reg_num = $currentYear . $currentMonth . '-000' .  $reg_cert_new;
                            break;
                        case '2':
                            $reg_num = $currentYear . $currentMonth . '-00' .  $reg_cert_new;
                            break;
                        case '3':
                            $reg_num = $currentYear . $currentMonth . '-0' .  $reg_cert_new;
                            break;
                        case '4':
                            $reg_num = $currentYear . $currentMonth . '-' .  $reg_cert_new;
                            break;
                    }

                    $reg_num_format = $cln . ' - ' .  $reg_num;
                }

                $pdf->Cell(70, 0, $reg_num_format, 0, 1, 'R', 0, '', 0);

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
                    $imagePath = storage_path("app/public/traineepic/" . $crew->trainee->imagepath);
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
                if ($course_type != 1 && $course_type != 8) {
                    $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
                } else {
                    $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                }

                //SIGNATURE ALIGNMENT
                $coc_gm_sign_x = $schedule->course->cocgmesignX;
                $coc_gm_sign_y = $schedule->course->cocgmesignY;
                $pdf->SetFont('times', 'BI', 25);
                $pdf->SetXY($coc_gm_sign_x, $coc_gm_sign_y);
                $pdf->setJPEGQuality(100);

                if ($course_type != 1) {
                    $imagePath = public_path("/storage/uploads/esign/" . "clemente.png");
                } else {
                    $imagePath = null;
                }
                $pdf->Image($imagePath, $coc_gm_sign_x, $coc_gm_sign_y, 48, 28, '', '', '', '', false, 300);
            }

            if (!$cert_history) {
                $new_cert = new tblcertificatehistory;
                $new_cert->traineeid = $crew->traineeid;
                $new_cert->courseid = $crew->courseid;
                $new_cert->enroledid = $crew->enroledid;
                $new_cert->certificatenumber = $cert_num;
                $new_cert->registrationnumber = $reg_num;
                $new_cert->controlnumber = $crew->controlnumber;
                if ($crew->course->type->coursetypeid == 3 || $crew->course->type->coursetypeid == 4) {
                    $new_cert->certserialnumber = $last_serialnumber->serialnumber++;
                }
                $new_cert->cln_type = $cln; // Set cln_type
                $new_cert->issued_by = Auth::user()->formal_name();
                $new_cert->save();
                $last_number_reg->save();
                $schedule->course->save();
                $last_serialnumber->save();
                $crew->save();
            }
        }

        $pdf->Output();
    }


    public function remedial_certificate($enroledid)
    {

        $this->enroledid = $enroledid;
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $schedule = tblremedial::where('enroledid', $enroledid)->first();

        // Check if schedule is not found
        if (!$schedule) {
            abort(404); // Display 404 error
        }

        $crew = tblenroled::where('enroledid', $enroledid)->where('passid', 1)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->first();



        $course_type = $schedule->schedule->course->type->coursetypeid;

        $last_number_reg = tbllastregistrationnumber::find(1);
        $last_serialnumber = tbltrainingcertserialnumber::find(1);

        //date
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->schedule->enddateformat);

        $formattedStartDate = $trainingStartDate->format('F j');
        $formattedEndDate = $trainingEndDate->format('j, Y');

        $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;

        $dateissue = date("dS", $trainingEndDate->timestamp) . " day of " . date("F", $trainingEndDate->timestamp) . " " . date("Y", $trainingEndDate->timestamp);


        $templatePath = storage_path('app/public/uploads/' . $schedule->schedule->course->certificatepath);

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Certificates');
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
            // if ($course_type != "2") {
            //     $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
            //     //NMC Certificate Paper
            //     $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            //     $pdf->useImportedPage($templateId, 0, 0, 210);
            // } else {
            //     $imagePath = storage_path("app/public/uploads/certificatetemplate/Upgrading Certificate Paper.jpg");
            //     //Upgrading Certificate Paper
            //     $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            //     $pdf->useImportedPage($templateId, 0, 0, 210);
            // }
            // Set additional content

            //CERTIFICATE ALIGNMENT
            $certAlignment = explode(',', $schedule->schedule->course->certificatenumberalignment);
            switch ($schedule->schedule->course->certfontid) {
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
            $pdf->SetFont($certnumfont, $schedule->schedule->course->certfontstyle->fontstylevalue, $schedule->schedule->course->certfontsizeid);
            $pdf->SetXY($certAlignment[0], $certAlignment[1]);

            if ($cert_history) {
                $cert_num = $cert_history->certificatenumber;
            } else {
                switch (strlen($schedule->schedule->course->lastcertificatenumber)) {
                    case '1':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-000' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                    case '2':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-00' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                    case '3':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-0' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                    case '4':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                }
            }

            $pdf->Cell(70, 0, $cert_num, 0, 1, 'R', 0, '', 0);

            //REGISTRATION ALIGNMENT
            $regAlignment = explode(',', $schedule->schedule->course->registrationnumberalignment);

            $pdf->SetFont($certnumfont, $schedule->schedule->course->certfontstyle->fontstylevalue, $schedule->schedule->course->certfontsizeid);
            $pdf->SetXY($regAlignment[0], $regAlignment[1]);

            if ($cert_history) {
                $reg_number = $cert_history->registrationnumber;
                if ($crew->cln_id) {
                    $cln = $crew->cln->cln_type;
                    $cert_history->cln_type = $cln;
                    $cert_history->controlnumber = $crew->controlnumber;
                    $cert_history->save();
                    $reg_num = $cln . ' - ' . $reg_number;
                } else {
                    $reg_num = $reg_number;
                }
            } else {
                $cln = $crew->cln_id ? $crew->cln->cln_type : '';
                $reg_cert_new = $currentYear . $currentMonth . '-' . $last_number_reg->lastregistrationnumber++;
                $reg_num = $cln . ' - ' . $reg_cert_new;
            }

            $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

            //NAME ALIGNMENT
            $nameAlignment = explode(',', $schedule->schedule->course->namealignment);
            switch ($schedule->schedule->course->crewnamefontid) {
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
            $pdf->SetFont($arialblack, $schedule->schedule->course->crewnamefontstyle->fontstylevalue,  $schedule->schedule->course->crewnamefontsizeid);
            $pdf->SetXY($nameAlignment[0], $nameAlignment[1]);
            // $pdf->Cell(210, 0, utf8_decode($crew->trainee->certificate_name()), 0, 1, 'C', 0, '', 0);
            $pdf->Cell(210, 0, $crew->trainee->certificate_name(), 0, 1, 'C', 0, '', 0);

            //BIRTHDAY ALIGNMENT
            $birthAlignment = explode(',', $schedule->schedule->course->birthdayalignment);
            switch ($schedule->schedule->course->birthdayfontid) {
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
            $pdf->SetFont($certnumfont, $schedule->schedule->course->birthdayfontstyle->fontstylevalue,  $schedule->schedule->course->birthdayfontsizeid);
            $dob = $crew->trainee->birthday;
            $dobCarbon = Carbon::parse($dob)->format('F d, Y');
            $new_format_dob = "(DOB : " . $dobCarbon . ")";
            // Calculate the width of the name text
            $pdf->SetXY($birthAlignment[0], $birthAlignment[1]);
            if ($course_type != 1) {
                $pdf->Cell(210, 0, $new_format_dob, 0, 1, 'C', 0, '', 0);
            }

            //PICTURE ALIGNMENT
            $pictureAlignment = explode(',', $schedule->schedule->course->picturealignment);
            $pdf->SetFont('times', 'BI', 25);
            $pdf->SetXY($pictureAlignment[0], $pictureAlignment[1]);
            $pdf->setJPEGQuality(100);

            if ($crew->trainee->imagepath) {
                $imagePath = public_path("/storage/uploads/traineepic/" . $crew->trainee->imagepath);
            } else {
                $imagePath = public_path("assets/images/oesximg/noimageavailable.jpg");
            }
            $pdf->Image($imagePath, $pictureAlignment[0], $pictureAlignment[1], 29.5, 28, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);


            $qrcodeAlignment = explode(',', $schedule->schedule->course->qrcodealignment);
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
            switch ($schedule->schedule->course->remarksfontid) {
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
            $pdf->SetFont($certnumfont, $schedule->schedule->course->remarksfontstyle->fontstylevalue, $schedule->schedule->course->remarksfontsizeid);
            $remarksAlignment = explode(',', $schedule->schedule->course->remarksalignment);
            $remarks = $schedule->schedule->course->certificateremarks;
            $certificateremarks = str_replace("trainingdate",  $trainingdateFormatted, $remarks);
            $certificateremarksmain = str_replace("practicaldate", $trainingdateFormatted, str_replace("dateissued", $dateissue, $certificateremarks));
            if ($course_type != 1 && $course_type != 8) {
                $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
            } else {
                $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            }
        }

        if (!$cert_history) {
            $new_cert = new tblcertificatehistory;
            $new_cert->traineeid = $crew->traineeid;
            $new_cert->courseid = $crew->courseid;
            $new_cert->enroledid = $crew->enroledid;
            $new_cert->certificatenumber = $cert_num;
            $new_cert->registrationnumber = $reg_cert_new;
            $new_cert->controlnumber = $crew->controlnumber;
            if ($crew->course->type->coursetypeid == 3 || $crew->course->type->coursetypeid == 4) {
                $new_cert->certserialnumber = $last_serialnumber->serialnumber++;
            }
            $new_cert->cln_type = $cln; // Set cln_type
            $new_cert->issued_by = Auth::user()->formal_name();
            $new_cert->save();
            $last_number_reg->save();
            $schedule->schedule->course->save();
            $last_serialnumber->save();
            $crew->save();
        }

        $pdf->Output();
    }

    public function viewSoloPdf()
    {
        $enroledid = Session::get('enroled_id');

        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $crew = tblenroled::find($enroledid);

        $schedule = tblcourseschedule::find($crew->scheduleid);
        $course_type = $schedule->course->type->coursetypeid;

        //date
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        $formattedStartDate = $trainingStartDate->format('F j');
        $formattedEndDate = $trainingEndDate->format('j, Y');

        $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;

        // $practicaltrainingdate = "February 14-16, 2023";
        $dateissue = date("dS") . " day of " . date("F") . " " . date("Y");
        // $mvvessel = "M.V. Stork";


        $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepath);

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
            } else {
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


            //RED COLOR SERIAL
            $pdf->SetFont($arialblack, 'B', 11);
            $pdf->SetXY(170, 15);
            $pdf->SetTextColor(255, 0, 0);

            if ($cert_history) {
                $cert_serial = $cert_history->controlnumber;
            } else {
                $cert_serial = '';
            }

            if ($crew->course->type->coursetypeid == 3 || $crew->course->type->coursetypeid == 4) {
                $pdf->Cell(0, 0, $cert_serial, 0, 1, 'L', 0, '', 0);
            }

            $pdf->SetTextColor(0, 0, 0);


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
                    $cert_history->controlnumber = $crew->controlnumber;
                    $cert_history->save();
                    $reg_num = $reg_number;
                    $reg_num_format = $cln . ' - ' .  $reg_num;
                } else {
                    $reg_num = $reg_number;
                    $reg_num_format =  $reg_num;
                }
            }

            $pdf->Cell(70, 0, $reg_num_format, 0, 1, 'R', 0, '', 0);

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
                $imagePath = storage_path("app/public/traineepic/"  . $crew->trainee->imagepath);
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
            if ($course_type != 1 && $course_type != 8) {
                $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
            } else {
                $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            }


            //SIGNATURE ALIGNMENT
            $coc_gm_sign_x = $schedule->course->cocgmesignX;
            $coc_gm_sign_y = $schedule->course->cocgmesignY;
            $pdf->SetFont('times', 'BI', 25);
            $pdf->SetXY($coc_gm_sign_x, $coc_gm_sign_y);
            $pdf->setJPEGQuality(100);

            if ($course_type != 1) {
                $imagePath = public_path("/storage/uploads/esign/" . "clemente.png");
            } else {
                $imagePath = null;
            }
            $pdf->Image($imagePath, $coc_gm_sign_x, $coc_gm_sign_y, 48, 28, '', '', '', '', false, 300);
        }
        $pdf->Output();
    }

    public function previewPdf($course_id)
    {
        $this->course_id = $course_id;
        $course = tblcourses::find($course_id);
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);

        $course_type = $course->type->coursetypeid;

        $last_number = tbllastregistrationnumber::find(1)->lastregistrationnumber;
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingdateFormatted = "February 14-16, 2023";
        $practicaltrainingdate = "February 15-16, 2023";
        $dateissue = date("dS") . " day of " . date("F") . " " . date("Y");
        // $mvvessel = "M.V. Stork";


        $templatePath = storage_path('app/public/uploads/') . $course->certificatepath;
        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Preview Certificate');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';



        $pdf->setSourceFile($templatePath);
        // for ($i = 1; $i <= $pageCount; $i++) {
        $pageWidth = 210; // A4 width in points
        $pageHeight = 297; // A4 height in points

        // Add a page
        $pdf->AddPage('P', [$pageWidth, $pageHeight]);
        // Set content from existing PDF

        // Import the first page of the existing PDF

        $templateId = $pdf->importPage(1);
        $pdf->useTemplate($templateId);
        if ($course_type != "2") {
            $imagePath = public_path("storage/uploads/certificatetemplate/NMCCertificatePaper.jpg");
            //NMC Certificate Paper
            $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            $pdf->useImportedPage($templateId, 0, 0, 210);
        } else {
            $imagePath = public_path("storage/uploads/certificatetemplate/Upgrading Certificate Paper.jpg");
            //Upgrading Certificate Paper
            $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            $pdf->useImportedPage($templateId, 0, 0, 210);
        }
        // Set additional content

        //CERTIFICATE ALIGNMENT
        $certAlignment = explode(',', $course->certificatenumberalignment);
        switch ($course->certfontid) {
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
        $pdf->SetFont($certnumfont, $course->certfontstyle->fontstylevalue, $course->certfontsizeid);
        $pdf->SetXY($certAlignment[0], $certAlignment[1]);

        $certnum_length = $course->lastcertificatenumber;

        switch (strlen($certnum_length)) {
            case '1':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . '000' . $course->lastcertificatenumber++;
                break;
            case '2':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . '00' . $course->lastcertificatenumber++;
                break;
            case '3':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . '0' . $course->lastcertificatenumber++;
                break;
            case '4':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . $course->lastcertificatenumber++;
                break;
        }
        $pdf->Cell(70, 0, $cert_num, 0, 1, 'R', 0, '', 0);

        //REGISTRATION ALIGNMENT
        $regAlignment = explode(',', $course->registrationnumberalignment);

        $pdf->SetFont($certnumfont, $course->certfontstyle->fontstylevalue, $course->certfontsizeid);
        $pdf->SetXY($regAlignment[0], $regAlignment[1]);

        $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number++;
        $reg_num = $reg_cert_new;
        $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

        //NAME ALIGNMENT
        $nameAlignment = explode(',', $course->namealignment);
        switch ($course->crewnamefontid) {
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
        $pdf->SetFont($certnumfont, $course->crewnamefontstyle->fontstylevalue,  $course->crewnamefontsizeid);
        $pdf->SetXY($nameAlignment[0], $nameAlignment[1]);
        $sample_name = "FIRST NAME MIDDLE INITIAL LAST NAME";
        // $pdf->Cell(210, 0, utf8_decode($crew->trainee->certificate_name()), 0, 1, 'C', 0, '', 0);
        $pdf->Cell(210, 0, $sample_name, 0, 1, 'C', 0, '', 0);

        //BIRTHDAY ALIGNMENT
        $birthAlignment = explode(',', $course->birthdayalignment);
        switch ($course->birthdayfontid) {
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
        $pdf->SetFont($certnumfont, $course->birthdayfontstyle->fontstylevalue,  $course->birthdayfontsizeid);
        $dob = '(DOB : MM DD YYYY)';
        // Calculate the width of the name text
        $pdf->SetXY($birthAlignment[0], $birthAlignment[1]);
        if ($course_type != 1) {
            $pdf->Cell(210, 0, $dob, 0, 1, 'C', 0, '', 0);
        }

        //PICTURE ALIGNMENT
        $pictureAlignment = explode(',', $course->picturealignment);
        $pdf->SetFont('times', 'BI', 25);
        $pdf->SetXY($pictureAlignment[0], $pictureAlignment[1]);
        $pdf->setJPEGQuality(100);

        $imagePath = public_path("assets/images/oesximg/noimageavailable.jpg");
        $pdf->Image($imagePath, $pictureAlignment[0], $pictureAlignment[1], 29.5, 28, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

        $qrcodeAlignment = explode(',', $course->qrcodealignment);
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $pdf->write2DBarcode('http://netionline.neti.com.ph/modules/traineehistory.php?enroledid=', 'QRCODE,L', $qrcodeAlignment[0], $qrcodeAlignment[1], 18, 16, $style, 'N');

        //REMARKS ALIGNMENT
        switch ($course->remarksfontid) {
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
        $pdf->SetFont($certnumfont, $course->remarksfontstyle->fontstylevalue, $course->remarksfontsizeid);
        $remarksAlignment = explode(',', $course->remarksalignment);
        $remarks = $course->certificateremarks;
        $certificateremarks = str_replace("trainingdate",  $trainingdateFormatted, $remarks);
        $certificateremarksmain = str_replace("practicaldate", $practicaltrainingdate, str_replace("dateissued", $dateissue, $certificateremarks));
        if ($course_type != 1 && $course_type != 8) {
            $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        } else {
            $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        }


        //SIGNATURE ALIGNMENT
        $coc_gm_sign_x = $course->cocgmesignX;
        $coc_gm_sign_y = $course->cocgmesignY;
        $pdf->SetFont('times', 'BI', 25);
        $pdf->SetXY($coc_gm_sign_x, $coc_gm_sign_y);
        $pdf->setJPEGQuality(100);

        if ($course_type != 1) {
            $imagePath = public_path("/storage/uploads/esign/" . "clemente.png");
        } else {
            $imagePath = null;
        }
        $pdf->Image($imagePath, $coc_gm_sign_x, $coc_gm_sign_y, 48, 28, '', '', '', '', false, 300);
        $pdf->Output();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-certificate-component');
    }
}
