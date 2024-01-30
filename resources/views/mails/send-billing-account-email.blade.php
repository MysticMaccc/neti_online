<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h4>Dear {{$user->formal_name()}},</h4>

    <p>I hope this email finds you well. As part of your enrollment, we would like to provide you with the bank account details for Payment in your enrollment. Please find the relevant information below:</p>

    <h4>
        Bank Name: {{$billing->bankname}} <br>
        Account Holder's Name: {{$billing->accountname}} <br>
        Account Number: {{$billing->accountnumber}} <br>
    </h4>

    <table>
        <tr>
            <td colspan="10" class="center">
                <b>SUMMARY</b> 
            </td>
        </tr>
        <tr>
            <td colspan="3"><b>Couse Training:</b> <b><i><u>{{$enrol->course->coursename}}</u></i></b></td>
            <td colspan="2">Date Training: <i>{{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}</i> </td>
            <td colspan="5">Reference #: <i>{{$enrol->registrationcode}}</i> </td>
        </tr>
        <tr>
            <td colspan="2"><b>Package: </b> </td>
            <td colspan="3"><i> The Selected Package is #{{$enrol->t_fee_package}}
                    @if ($enrol->t_fee_package == 1)
                    (Training fee only)
                    @elseif ($enrol->t_fee_package == 2)
                    (Training schedule with Lunch Meal and Polo Shirt.)
                    @elseif ($enrol->t_fee_package == 3)
                    (Training schedule with Lunch Meal, Polo Shirt & Bus Round Trip.)
                    @elseif ($enrol->t_fee_package == 4)
                    (Training schedule with Lunch Meal, Polo Shirt and Daily Bus Round Trip.)
                    @endif
                </i>
            </td>
            <td colspan="2">Package Price: </td>
            <td colspan="3">₱ {{number_format($enrol->t_fee_price,2,'.',',')}}</td>
        </tr>
        <tr>
            <td colspan="10" class="center"> <b>DORMITORY BILL & MEAL</b> </td>
        </tr>
        <tr>
            <td colspan="3"><b>Room type:</b></td>
            @if ($enrol->dorm)
                <td colspan="2">{{$enrol->dorm->dorm}}</td>
                @else
                <td colspan="2"><i> N/A </i></td>
            @endif
            <td colspan="3"> <b>Dorm & Meal fee:</b></td>
            @if ($enrol->dorm_price)
                <td colspan="2">₱{{number_format($enrol->dorm_price + $enrol->meal_price, 2, '.', ',')}}</td>
                @else
                <td colspan="2"><i> N/A </i></td>
            @endif
        </tr>
        <tr>
            <td colspan="3"><b>Check in:</b></td>
            @if ($enrol->checkindate)
                <td colspan="2">{{$enrol->checkindate}}</td>
                @else
                <td colspan="2"><i> N/A </i></td>
            @endif
            <td colspan="3"><b>Check out:</b></td>
            @if ($enrol->checkindate)
                <td colspan="2">{{$enrol->checkoutdate}}</td>
                @else
                <td colspan="2"><i> N/A </i></td>
            @endif
        </tr>
        <tr>
            <td colspan="5"> <b>Transporation:</b> </td>
            <td colspan="5"> <i>
            @if ($enrol->busmodeid == 1)
                Round trip
            @elseif ($enrol->busmodeid == 2)
                Daily Round Trip
            @else
                None
            @endif </i> </td>
        </tr>

        <tr>
            <td colspan="5"><b> TOTAL AMOUNT (Need to pay):</b> </td>
            <td colspan="5"><i>₱ {{ number_format($enrol->total, 2, '.', ',') }}</i></td>
        </tr>
    </table>

    <i>
        Please ensure that the account details are accurately noted, as any discrepancies could cause delays in processing transactions.

        If you have any questions or require further clarification, please do not hesitate to reach out to our customer service team at Business Operation Department. Our team will be more than happy to assist you.

        We value your continued partnership, and we look forward to serving you better in the future.

        Thank you for choosing {{$billing->accountname}}. We truly appreciate your trust and support.
    </i>

    <h4>
        Best regards,
        <br>
        <br>
        Business Operation Department <br>
        {{$billing->accountname}}
    </h4>


    <small>Note: This email contains confidential information intended only for the recipient. If you have received this email in error, please notify the sender immediately and delete it from your system.</small>
</body>

</html>