<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use Livewire\Component;
use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tbllastregistrationnumber;
use App\Models\tbltrainingcertserialnumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;


class AGeneratePDOSComponent extends Component
{
    public $training_id;
    public $schedule;
    public $course_id;

    public function viewPdf($training_id)
    {
        $this->training_id = $training_id;

        $schedule = tblcourseschedule::find($this->training_id);
        $crews = tblenroled::where('scheduleid', $this->training_id)->where('pendingid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);

        $last_number_reg = tbllastregistrationnumber::find(1);
        $last_serialnumber = tbltrainingcertserialnumber::find(1);

        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $templatePath = storage_path('app/public/uploads/') . $schedule->course->certificatepath;

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Preview Certificate');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        // Initialize crew counter and crew per page limit
        $crewCounter = 0;
        $crewPerPageLimit = 2;

        // Loop through crews
        foreach ($crews as $index => $crew) {
            $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)->where('enroledid', $crew->enroledid)->where('traineeid', $crew->traineeid)->first();

            if ($crewCounter % $crewPerPageLimit == 0) {
                // Start a new page for every $crewPerPageLimit crew members
                $pageWidth = 210; // A4 width in points
                $pageHeight = 297; // A4 height in points
                $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                $pdf->setSourceFile($templatePath);
                $templateId = $pdf->importPage(1);
                // Import the first page of the existing PDF outside of the loop (only once per page)
                $pdf->useTemplate($templateId);

                // Add the sample code you provided
                $pdf->SetFont('times', '', 8);
                $pdf->SetXY(115, 42);

                // $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number++;
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

                // Set crew information (your existing code)
                $pdf->SetFont('times', 'B', 10);
                $pdf->SetXY(70, 50);
                $name = $crew->trainee->certificate_name();
                $pdf->Cell(210, 0, $name, 0, 1, 'L', 0, '', 0);

                //SKILLS OF OCCU
                $pdf->SetFont('times', 'B', 10);
                $pdf->SetXY(70, 57);
                $occu = $crew->trainee->rank->rank;
                $pdf->Cell(210, 0, $occu, 0, 1, 'L', 0, '', 0);

                //COUNTRY
                $pdf->SetFont('times', 'B', 10);
                $pdf->SetXY(70, 63.5);
                $country = $crew->pdos_destination;
                $pdf->Cell(210, 0, $country, 0, 1, 'L', 0, '', 0);

                //AGENCY
                $pdf->SetFont('times', 'B', 10);
                $pdf->SetXY(70, 71);
                $agency =  $crew->trainee->company->company;
                $pdf->Cell(210, 0, $agency, 0, 1, 'L', 0, '', 0);

                //FOREIGN PRINCIPAL
                $pdf->SetFont('times', 'B', 10);
                $pdf->SetXY(70, 78);
                $principal = $crew->pdos_principal;
                $pdf->Cell(210, 0, $principal, 0, 1, 'L', 0, '', 0);

                //FOREIGN EMPLOYER
                $pdf->SetFont('times', 'B', 10);
                $pdf->SetXY(70, 84.5);
                $employer = $crew->pdos_employer;
                $pdf->Cell(210, 0, $employer, 0, 1, 'L', 0, '', 0);

                //EXPIRY
                $pdf->SetFont('times', 'B', 10);
                $pdf->SetXY(70, 91.5);
                $expiry = $crew->pdos_expiry;
                $pdf->Cell(210, 0, $expiry, 0, 1, 'L', 0, '', 0);



                // Check if there's another crew member available
                $tempIndex = $index + 1;
                if ($tempIndex < count($crews)) {

                    $nextCrew = $crews[$tempIndex];
                    $cert_history1 = tblcertificatehistory::where('courseid', $nextCrew->courseid)->where('enroledid', $crew->enroledid)->where('traineeid', $nextCrew->traineeid)->first();

                    // Set crew information for the second crew member
                    $pdf->SetFont('times', '', 8);
                    $pdf->SetXY(115, 186);
                    // $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number_reg->lastregistrationnumber++;
                    // $reg_num = $reg_cert_new;

                    if ($cert_history1) {
                        $reg_number = $cert_history->registrationnumber;
                        if ($nextCrew->cln_id) {
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
                        $cln = $nextCrew->cln_id ? $crew->cln->cln_type : '';
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

                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(70, 194);
                    $name1 = $nextCrew->trainee->certificate_name();
                    $pdf->Cell(210, 0, $name1, 0, 1, 'L', 0, '', 0);

                    //SKILLS OF OCCU
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(70, 200);
                    $occu = $nextCrew->trainee->rank->rank;
                    $pdf->Cell(210, 0, $occu, 0, 1, 'L', 0, '', 0);

                    //COUNTRY
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(70, 207.5);
                    $country = $nextCrew->pdos_destination;
                    $pdf->Cell(210, 0, $country, 0, 1, 'L', 0, '', 0);

                    //AGENCY
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(70, 213.5);
                    $agency = $nextCrew->trainee->company->company;
                    $pdf->Cell(210, 0, $agency, 0, 1, 'L', 0, '', 0);

                    //FOREIGN PRINCIPAL
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(70, 221.5);
                    $principal = $nextCrew->pdos_principal;
                    $pdf->Cell(210, 0, $principal, 0, 1, 'L', 0, '', 0);

                    //FOREIGN EMPLOYER
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(70, 228);
                    $employer = $nextCrew->pdos_employer;
                    $pdf->Cell(210, 0, $employer, 0, 1, 'L', 0, '', 0);

                    //EXPIRY
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(70, 235);
                    $expiry = $nextCrew->pdos_expiry;
                    $pdf->Cell(210, 0, $expiry, 0, 1, 'L', 0, '', 0);
                }
            }
            $crewCounter++;

            if (!$cert_history) {
                $new_cert = new tblcertificatehistory;
                $new_cert->traineeid = $crew->traineeid;
                $new_cert->courseid = $crew->courseid;
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
                $new_cert->certificatenumber = $cert_num;
                $new_cert->registrationnumber = $currentYear . $currentMonth . '-' . $last_number_reg->lastregistrationnumber++;
                $new_cert->enroledid = $crew->enroledid;
                $new_cert->controlnumber = $crew->controlnumber;
                $new_cert->certserialnumber = $last_serialnumber->serialnumber++;
                $new_cert->cln_type = $reg_num; // Set cln_type
                $new_cert->save();
                $last_number_reg->save();
                $schedule->course->save();
                $last_serialnumber->save();
            }
        }

        // Output the PDF
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

        $last_number = tbllastregistrationnumber::find(1)->lastregistrationnumber;
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');


        $templatePath = storage_path('app/public/uploads/') . $course->certificatepath;

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Preview Certificate');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));



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

        // Set additional content
        //REG ALIGNMENT
        $pdf->SetFont('times', '', 8);
        $pdf->SetXY(115, 42);
        $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number++;
        $reg_num = $reg_cert_new;
        $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

        //NAME OF OFW
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 50);
        $name = "FIRST NAME MIDDLE INITIAL LAST NAME";
        $pdf->Cell(210, 0, $name, 0, 1, 'L', 0, '', 0);

        //SKILLS OF OCCU
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 57);
        $occu = "SAMPLE DATA";
        $pdf->Cell(210, 0, $occu, 0, 1, 'L', 0, '', 0);

        //COUNTRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 63.5);
        $country = "SAMPLE DATA";
        $pdf->Cell(210, 0, $country, 0, 1, 'L', 0, '', 0);

        //AGENCY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 71);
        $agency = "SAMPLE DATA";
        $pdf->Cell(210, 0, $agency, 0, 1, 'L', 0, '', 0);

        //FOREIGN PRINCIPAL
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 78);
        $principal = "SAMPLE DATA";
        $pdf->Cell(210, 0, $principal, 0, 1, 'L', 0, '', 0);

        //FOREIGN EMPLOYER
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 84.5);
        $employer = "SAMPLE DATA";
        $pdf->Cell(210, 0, $employer, 0, 1, 'L', 0, '', 0);

        //EXPIRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 91.5);
        $expiry = "SAMPLE DATA";
        $pdf->Cell(210, 0, $expiry, 0, 1, 'L', 0, '', 0);






        //REG ALIGNMENT
        $pdf->SetFont('times', '', 8);
        $pdf->SetXY(115, 186);
        $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number++;
        $reg_num = $reg_cert_new;
        $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

        //NAME OF OFW
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 194);
        $name = "FIRST NAME MIDDLE INITIAL LAST NAME";
        $pdf->Cell(210, 0, $name, 0, 1, 'L', 0, '', 0);

        //SKILLS OF OCCU
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 200);
        $occu = "SAMPLE DATA";
        $pdf->Cell(210, 0, $occu, 0, 1, 'L', 0, '', 0);

        //COUNTRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 207.5);
        $country = "SAMPLE DATA";
        $pdf->Cell(210, 0, $country, 0, 1, 'L', 0, '', 0);

        //AGENCY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 213.5);
        $agency = "SAMPLE DATA";
        $pdf->Cell(210, 0, $agency, 0, 1, 'L', 0, '', 0);

        //FOREIGN PRINCIPAL
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 221.5);
        $principal = "SAMPLE DATA";
        $pdf->Cell(210, 0, $principal, 0, 1, 'L', 0, '', 0);

        //FOREIGN EMPLOYER
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 228);
        $employer = "SAMPLE DATA";
        $pdf->Cell(210, 0, $employer, 0, 1, 'L', 0, '', 0);

        //EXPIRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 235);
        $expiry = "SAMPLE DATA";
        $pdf->Cell(210, 0, $expiry, 0, 1, 'L', 0, '', 0);

        $pdf->Output();
    }


    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-p-d-o-s-component');
    }
}
