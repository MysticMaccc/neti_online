<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMISSION SLIP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        * {
            /* font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; */
            font-family: 'arial';
        }

        body {
            margin-bottom: 20px;
            font-size: x-small;
            /* background-image: url('assets/img/background.png'); */
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }

        table {
            font-size: x-small;
            margin-bottom: 10px;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
        }

        .pagenum:before {
            content: counter(page);
        }


        .logo-container {
            text-align: center;
        }

        .logo-container img {
            max-width: 40%;
            height: auto;
            display: inline-block;
            /* Use one of the following options to move the image up */
            /* Option 1: Use vertical-align */
            vertical-align: middle;
            /* Option 2: Use margin-top */
            margin-top: -40px;
        }

        .company-info pre {
            margin: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        .border-td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            text-align: left;
            background-color: lightgray;
        }

        td {
            background-color: white;
        }

        td:nth-child(1) {
            width: 30%;
        }

        td:nth-child(2) {
            width: 5%;
        }

        td:nth-child(3) {
            width: 60%;
        }

        td:nth-child(4) {
            width: 23%;
        }

        td:nth-child(5),
        td:nth-child(6) {
            width: 10%;
        }

        td:nth-child(7) {
            width: 30%;
        }

        td:nth-child(8),
        td:nth-child(9) {
            width: 15%;
        }

        .indent {
            padding-left: 20px;
            /* Adjust the value as needed */
        }
    </style>
</head>

<body>
    <header>
        <div class="text-right" style="margin-top: -10px;">
            <small>F-NETI-023</small>
        </div>
        <div class="logo-container">
            <img src="{{ public_path('assets/images/oesximg/NETI.png') }}" alt="" width="270" height="auto">
            <br>
            <small style="line-height: 1.2;">Knowledge Avenue, Carmeltown, Canlubang, Calamba City 4037, Laguna Philippines <br>
                Tel. No. : (+63)2 8908 - 4900 / (+63)2 8554 - 3888 * Fax No. : (049) 508 - 8679 <br>
                *Online enrollment url : netionline.neti.com.ph <br>
                *website url: www.neti.com.ph * email : neti@neti.com.ph <br></small>
        </div>
    </header>

    <footer class="w-100">
        <table class="w-100">
            <tr>
                <td style="width:70%">
                    <small><i>This document is system generated.</i></small>
                </td>
                <td class="text-right" style="width:30%">
                    <small><i>Page <span class="pagenum"></span></i></small>
                </td>
            </tr>
        </table>
    </footer>

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="text-align: center; font-weight: bold; margin-top:100px; font-size:large;">
                    GUIDELINES FOR ON-SITE <br> TRAINING
                </div>
                <div style="font-size:medium; margin-top:40px;">
                    <b>TRAINEES INFORMATION</b> <br>
                    <table style="width: 100%; font-size: medium;">
                        <tr>
                            <td style="width: 50%; text-transform:uppercase;">
                                <b>NAME:</b> {{$enrol->trainee->formal_name()}} <br>
                                <b>RANK:</b> {{$enrol->trainee->rank->rank}} <br>
                                <b>CONTACT NO:</b> {{$enrol->trainee->contact_num}} <br>
                                <b>COMPANY:</b>{{$enrol->trainee->company->company}}
                            </td>
                            <td style="width: 50%;">
                                <div style="text-align: center;">
                                    @if ($enrol->trainee->imagepath)
                                        <img src="{{asset('storage/traineepic/'. $enrol->trainee->imagepath)}}" width="75" alt="avatar">
                                    @else
                                        <img src="{{asset('assets/images/avatar/avatar.jpg')}}"  width="100" alt="avatar">
                                    @endif
                                    <!-- <img src="{{ public_path('assets/images/avatar/avatar.jpg') }}" alt="" width="100" height="auto"> -->
                                    <p>{{ $enrol->trainee->traineeid }}</p>
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($enrol->trainee->traineeid, 'C128') }}" alt="Barcode" style="display: block; ">
                                </div>

                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </table>
                    <table class="border-td" style="width: 90%; font-size: xx-small;">
                        <tr>
                            <th colspan="3">
                                Admission Slip #: {{$enrol->registrationnumber}}
                            </th>
                        </tr>
                        <tr>
                            <th style="width: 20%;">CODE</th>
                            <th style="width: 60%;">
                                <b>COURSES ENROLLED / TRAINING DATE </b>
                            </th>
                            <th style="width: 20%;"> TYPE </th>
                        </tr>
                        <tbody>
                            <tr style="text-align: center;">
                                <td class="border-td" style="width: 20%;"> {{$enrol->course->coursecode}}</td>
                                <td class="border-td" style="width: 60%;">
                                    {{$enrol->course->coursename}}
                                </td>
                                <td class="border-td" style="width: 20%;">{{$enrol->course->mode->modeofdelivery}}</td>
                            </tr>
                            <th colspan="3" style="text-align: center;">TRAINING SCHEDULE</th>
                            <tr style="text-align: center;">
                                <td colspan="3" class="border-td">
                                    Days of Online:
                                    @if ($enrol->schedule->dateonlinefrom)
                                    @if ($enrol->schedule->dateonlinefrom === $enrol->schedule->dateonlineto)
                                    <i>{{$enrol->schedule->dateonlinefrom}}</i>
                                    @else
                                    <i>{{$enrol->schedule->dateonlinefrom}} - {{$enrol->schedule->dateonlineto}}</i>
                                    @endif
                                    @else
                                    <i> --no schedule for online--</i>
                                    @endif
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <td colspan="3" class="border-td">
                                    Days of Onsite:
                                    @if ($enrol->schedule->dateonsitefrom)
                                    @if ($enrol->schedule->dateonsitefrom === $enrol->schedule->dateonsiteto)
                                    <i>{{$enrol->schedule->dateonsitefrom}}</i>
                                    @else
                                    <i>{{$enrol->schedule->dateonsitefrom}} - {{$enrol->schedule->dateonsiteto}}</i>
                                    @endif
                                    @else
                                    <i> --no schedule for onsite--</i>
                                    @endif
                                </td>
                            </tr>
                            <th colspan="3" style="text-align: center;">
                                INCLUDE
                            </th>
                            <tr style="text-align: center;">
                                <td colspan="3">
                                    @if ($enrol->meal_price && $enrol->dorm_price && $enrol->busid)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->meal_price && $enrol->dorm_price)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->meal_price && $enrol->busid)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->dorm_price && $enrol->busid)
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->busid)
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->dorm_price)
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->meal_price)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
            <td style="width: 50%;">
                <div style="margin-top: 80px; padding: 10px; border: 1px solid black;">
                    <div style="text-align: justify; font-size:xx-small;">
                        <p><b>IMPORTANT REMINDERS:</b>
                            <br>1. All classes are conducted from 0800H to 1700H. <br>
                            <br>2. Please observe the proper dress code (polo shirt, dark slacks, black leather <br>
                            shoes). Wearing maong pants and rubber shoes is strictly prohibited. <br>
                            <br>3. Discipline, good manners and right conduct should be observed at all times. <br>
                            Littering, loitering, sleeping, smoking and lack of self-control due to influence 
                            of alcohol are grounds for termination of training. <br>
                            <br>4. If you will avail the shuttle service, please arrive on time at NYK-FIL <br>
                            Intramuros; the bus shall leave at exactly 0645H every Monday to Friday. <br>
                            <br>5. For those who will undergo Rapid test, please be at TMDC on or before 
                            0600H. <br>
                            <br>6. 'No admission slip and No Rapid Test result (negative) - no boarding of bus' 
                            policy shall apply. For those who will go directly to NETI, you may present a
                            digital copy of your admission slip upon entry. <br>
                            <br>7. All crews are required to scan the Safety Seal QR Code found at the
                            entrance for health declaration purposes. <br>
                            <br>8. NETI Safety Briefing and Orientation: <br>
                        <div class="indent">
                            <b>FOR TRAINEES ARRIVING ON MONDAY AND WEDNESDAY –</b> Upon arrival
                            at the training center, please proceed to the NDB 2
                            nd Floor.
                        </div>

                        <div class="indent">
                            <b>FOR OTHER TRAINEES –</b> Safety briefing shall be conducted on the bus
                            through video streaming (insert link here) and may proceed
                            immediately to your assigned classroom.
                        </div>
                        <br>7. 'No photo, no issuance of training certificate' is strictly observed to avoid
                        delay. For your convenience, you may upload your photos in your NETI OES
                        account or you can have your photos taken on-site on our photo center. <br>
                        <br>8. Cancellation of enrollment shall be made through online enrollment system
                        seven (7) working days prior start of your training. In case you cannot
                        cancel it online due to unavoidable circumstances, you may reach us by
                        calling (02) 8-908-4900 /(02) 8-554- 3888. Cutoff date for issuance of
                        admission slip is every Tuesday. <br>
                        <br>9. For walk-in trainees, payment should be settled in full at least one day
                        before your first day of training. Payments are strictly online.</p> <br>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>