<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblinstructordependents;
use App\Models\tblinstructoremploymentinformation;
use App\Models\User;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class IGenerateIntructorInformationSheetComponent extends Component
{

    public function viewPDF($hashid){
        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(false, 40);
        $pdf->SetMargins(60, 41, PDF_MARGIN_RIGHT);

        $userinfo = User::where('hash_id', '=', $hashid)->first();

        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/gothic.ttf', 'TrueTypeUnicode', '', 96);
        $templatePath = public_path() . '/instructortemplate/guest_lect_info_sheet.pdf';

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
                $pdf->SetFont($gothic, '', 8);
                $pdf->Cell(0, 0, strtoupper($userinfo->f_name.' '.$userinfo->m_name.' '.$userinfo->l_name), 0, 1, '', false, 0, '', 0);
                $pdf->Cell(0, 10, strtoupper('Sample address'), 0, 1, '', false, 0, '', 0);
                $pdf->Cell(0, 0, strtoupper('Sample address'), 0, 1, '', false, 0, '', 0);
                $imagePath = public_path("storage/uploads/instructorpic/".$userinfo->imagepath);
                // dd($imagePath);
                $pdf->Image($imagePath, 160.9, 41.2, $w = 16.5, $h = 16.1, $type = '', $link = '', $align = '', $resize = true, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false);
                // $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 20, '', false, false, 0);
                $pdf->SetXY(60,60.6);
                $pdf->Cell(0, 0, $userinfo->instructor->rank->rank, 0, 0, '', 0, '', 0);

                $pdf->SetXY(145.5,60.6);
                $pdf->Cell(0, 0, $userinfo->instructor->telephonenumber, 0, 1, '', 0, '', 0);

                $pdf->SetXY(60,64.5);
                $pdf->Cell(0, 0, $userinfo->instructor->nickname, 0, 0, '', 0, '', 0);

                $pdf->SetXY(145.5,64.5);
                $pdf->Cell(0, 0, $userinfo->instructor->mobilenumber, 0, 0, '', 0, '', 0);

                $birthday = date('Y, F j', strtotime($userinfo->birthday));
                $pdf->SetXY(60,68.8);
                $pdf->Cell(0, 0, $birthday, 0, 0, '', 0, '', 0);

                $pdf->SetXY(145,68.8);
                $pdf->Cell(0, 0, $userinfo->email, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,73);
                $pdf->Cell(0, 0, $userinfo->birthplace, 0, 0, '', 0, '', 0);

                $pdf->SetXY(145,73);
                $pdf->Cell(0, 0, $userinfo->instructor->sss, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,77);
                $pdf->Cell(0, 0, $userinfo->instructor->gender->gender, 0, 0, '', 0, '', 0);

                $pdf->SetXY(145,77);
                $pdf->Cell(0, 0, $userinfo->instructor->tin, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,81);
                $pdf->Cell(0, 0, $userinfo->instructor->civilstatus->civilstatus, 0, 0, '', 0, '', 0);

                $pdf->SetXY(145,81);
                $pdf->Cell(0, 0, $userinfo->instructor->pagibig, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,85.5);
                $pdf->Cell(0, 0, $userinfo->instructor->citizenship, 0, 0, '', 0, '', 0);

                $pdf->SetXY(145,85.5);
                $pdf->Cell(0, 0, $userinfo->instructor->philhealth, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,100);
                $pdf->Cell(0, 0, $userinfo->instructor->license, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,104);
                $pdf->Cell(0, 0, $userinfo->instructor->licensedateissued, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,108.2);
                $pdf->Cell(0, 0, $userinfo->instructor->liceseissuedby, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,115.6);
                $pdf->Cell(0, 0, $userinfo->instructor->degree, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,119.6);
                $pdf->Cell(0, 0, $userinfo->instructor->school, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,124);
                $pdf->Cell(0, 0, $userinfo->instructor->dategraduated, 0, 0, '', 0, '', 0);

                $pdf->SetXY(60,128.1);
                $pdf->Cell(0, 0, $userinfo->instructor->awardsreceived, 0, 0, '', 0, '', 0);

                $instructordependents = tblinstructordependents::where('instructorid', $userinfo->instructor->instructorid)->take(5)->get();

                $counter = 0;

                foreach ($instructordependents as $dependents) {
                    switch ($counter) {
                        case 1:
                        $yaxis = 151.1;
                        break;

                        case 2:
                        $yaxis = 155.3;
                        break;

                        case 3:
                        $yaxis = 159.5;
                        break;

                        case 4:
                        $yaxis = 163.5;
                        break;

                        default:
                        $yaxis = 147.1;
                        break;
                    }

                    $pdf->SetXY(25,$yaxis);
                    $pdf->Cell(0, 0, $dependents->dependentfullname, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(94,$yaxis);
                    $pdf->Cell(0, 0, $dependents->dependentrelationship, 0, 0, '', 0, '', 0);

                    $dependentbirthdate = date('Y, F j', strtotime($dependents->dependentbirthdate));
                    $pdf->SetXY(116,$yaxis);
                    $pdf->Cell(0, 0, $dependentbirthdate, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(145.6,$yaxis);
                    $pdf->Cell(0, 0, $dependents->dependentaddress, 0, 0, '', 0, '', 0);
                    $counter++;
                }



                $instructoremploymentinfo = tblinstructoremploymentinformation::where('instructorid', $userinfo->instructor->instructorid)->take(5)->get();

                $counter = 0;

                foreach ($instructoremploymentinfo as $employmentinfo) {
                    switch ($counter) {
                        case 1:
                        $yaxis = 200.3;
                        break;

                        case 2:
                        $yaxis = 204.5;
                        break;

                        case 3:
                        $yaxis = 208.5;
                        break;

                        case 4:
                        $yaxis = 212.5;
                        break;

                        default:
                        $yaxis = 196.1;
                        break;
                    }

                    $pdf->SetXY(17,$yaxis);
                    $pdf->Cell(0, 0, $employmentinfo->rank, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(60,$yaxis);
                    $pdf->Cell(0, 0, $employmentinfo->vessel, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(117,$yaxis);
                    $pdf->Cell(0, 0, $employmentinfo->vesseltype, 0, 0, '', 0, '', 0);

                    // Method 1: Using a regular expression to check for the "YYYY-MM-DD" format
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $employmentinfo->inclusivedate)) {
                        $inclusivedate = date("Y, F d", strtotime($employmentinfo->inclusivedate));
                    } else {
                        $inclusivedate = $employmentinfo->inclusivedate;
                    }
                    $pdf->SetXY(143.8,$yaxis);
                    $pdf->Cell(0, 0, $inclusivedate, 0, 0, '', 0, '', 0);
                    $counter++;
                }



                $pdf->SetXY(37,173.6);
                $pdf->Cell(0, 0, $userinfo->instructor->contactperson, 0, 0, '', 0, '', 0);

                $pdf->SetXY(115,173.6);
                $pdf->Cell(0, 0, $userinfo->instructor->contactpersonrelationship, 0, 0, '', 0, '', 0);

                $pdf->SetXY(156,173.6);
                $pdf->Cell(0, 0, $userinfo->instructor->contactpersonmobilenumber, 0, 0, '', 0, '', 0);

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $userinfo->instructor->datestartedwithTDG && !empty($userinfo->instructor->datestartedwithTDG))) {
                    $datestartedwithTDG = date("Y, F d", strtotime($userinfo->instructor->datestartedwithTDG));
                } else {
                    $datestartedwithTDG = $userinfo->instructor->datestartedwithTDG;
                }

                $datestartedwithTDG = date('Y, F d', strtotime($datestartedwithTDG));
                $pdf->SetXY(59.9,219.5);
                $pdf->Cell(0, 0, $datestartedwithTDG, 0, 0, '', 0, '', 0);

                $pdf->SetXY(59.9,223.6);
                $pdf->Cell(0, 0, $userinfo->instructor->awardsreceivedTDG, 0, 0, '', 0, '', 0);


                $counter = 0;

                foreach ($userinfo->instructor->instructorlicense->take(4) as $instructorlicense) {
                    switch ($counter) {
                        case 1:
                        $yaxis = 247.7;
                        break;

                        case 2:
                        $yaxis = 252;
                        break;

                        case 3:
                        $yaxis = 256;
                        break;

                        default:
                        $yaxis = 243.8;
                        break;
                    }

                    $pdf->SetXY(17,$yaxis);
                    $pdf->Cell(0, 0, $instructorlicense->license, 0, 0, '', 0, '', 0);

                    $dateofissue = date("Y, F d", strtotime($instructorlicense->dateofissue));

                    $pdf->SetXY(95,$yaxis);
                    $pdf->Cell(0, 0, $dateofissue, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(145,$yaxis);
                    $pdf->Cell(0, 0, $instructorlicense->issuingauthority, 0, 0, '', 0, '', 0);

                    $counter++;
                }

                $pdf->SetXY(134,274.6);
                $pdf->Cell(0, 0, $userinfo->formal_name(), 0, 0, '', 0, '', 0);



            }

        $pdf->Output();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.i-generate-intructor-information-sheet-component');
    }
}
