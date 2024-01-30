<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daily/Weekly Reports Form</title>
    <style>
        *{
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Style the table */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        /* Style the table header row */
        th {
            background-color: #a1a1a1;
            font-weight: bold;
            text-align: center;
            border: 1px solid #000;
            padding: 2px;
            font-size: 9px;
        }

        /* Style the table cells */
        td {
            text-align: center;
            border: 1px solid #000;
            padding: 2px;
            font-size: 8px;
        }

        /* Add a border to the last row of the table (optional) */
        tr:last-child td {
            border-bottom: 2px solid #000;
        }
    </style>
</head>
<body>
    <strong style="margin-left: 42%;">{{ strtoupper($type) }} REPORTS</strong>
    <br>
    <p style="margin-top: 1em; font-size: 12px;">Date Range: {{ $name }} <br>
        Status: @if ($status == 2)
        {{ strtoupper('Check Out') }}
        @elseif ($status == 1)
        {{ strtoupper('Check In') }}
        @elseif ($status == 4)
        {{ strtoupper('No Show') }}
        @else
        {{ strtoupper($status) }}
        @endif
    </p>

    @if ($type == 'daily')
            @php
                $count = 0;
            @endphp
            @foreach ($dailydate as $dailydate => $date)
                <table style="margin-top: 2em;">
                    <thead>
                        <tr>
                            <th colspan="12">{{ date("l - F d, 2023", strtotime($date)) }}</th>
                        </tr>
                        <tr>
                            <th>Room Type1</th>
                            <th>Room</th>
                            <th>Name</th>
                            <th>Training Date</th>
                            <th>Check In Date</th>
                            <th>Check Out Date</th>
                            <th>Company</th>
                            <th>Mode of Payment</th>
                            <th>Rank</th>
                            <th>Course</th>
                            <th>Total Lodging Rate</th>
                            <th>Total Food Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dailydatatable[$count] as $data)
                            @if ($data->roomtype)
                                <tr>
                                    <td>{{ $data->roomtype }}</td>
                                    <td>{{ $data->roomname }}</td>
                                    <td>{{ $data->l_name }} {{ $data->f_name }}</td>
                                    <td>{{ $data->datefrom }} - {{ $data->dateto }}</td>
                                    <td>{{ $data->checkindate }}</td>
                                    <td>{{ $data->checkoutdate }}</td>
                                    <td>{{ $data->company }}</td>
                                    <td>{{ $data->paymentmode }}</td>
                                    <td>{{ $data->rank }}</td>
                                    <td>{{ $data->coursename }}</td>
                                    
                                    <td> USD {{ $data->NonNykRoomPrice }}</td>
                                    <td> USD {{ $data->NonNykMealPrice }}</td>

                                    @php
                                        $totalroomrate += $data->NonNykRoomPrice;
                                        $totalmealrate += $data->NonNykMealPrice;
                                    @endphp
                                </tr>
                            @else
                                <tr>
                                    <td colspan="12">No Data To Preview</td>
                                </tr>
                            @endif
                            
                        @endforeach
                    </tbody>
                    <tfoot>
                            <tr>
                                <td colspan="12"><p style="font-size: 11px; font-weight: bold;"> Total Lodging Rate: {{ $totalroomrate }} <br>
                                    Total Meal Rate: {{ $totalmealrate }} <br>
                                    Total: {{ $total = $totalroomrate + $totalmealrate }} </p></td>
                            </tr>
                    </tfoot>
                </table>

                

                @php
                    $totalroomrate = 0;
                    $totalmealrate = 0;
                    $count++;
                @endphp
            @endforeach
            @php
                $count--;
            @endphp
            @for ($i = $count; $i >= 0; $i--)
                @foreach ($dailydatatable[$i] as $data)
                    @php
                        $totalroomrate += $data->NonNykRoomPrice;
                        $totalmealrate += $data->NonNykMealPrice;
                        $total = $totalroomrate + $totalmealrate;
                        $count++;
                    @endphp
                @endforeach
            @endfor

            <div style="margin-top: .5em; background: rgb(197, 197, 197);">
                <p style="font-size: 11px; font-weight: bold;"> Overall Total Lodging Rate: {{ $totalroomrate }} <br>
                Overall Total Meal Rate: {{ $totalmealrate }} <br>
                Overall Total: {{ $total = $totalroomrate + $totalmealrate }} </p>
            </div>

            

        
    @else
        <table>
            <thead>
                <tr>
                    <th colspan="12">{{ $datefrom->format("F d, Y") }} - {{ $dateto->format("F d, Y") }}</th>
                </tr>
                <tr>
                    <th>Room Type</th>
                    <th>Room</th>
                    <th>Name</th>
                    <th>Training Date</th>
                    <th>Check In Date</th>
                    <th>Check Out Date</th>
                    <th>Company</th>
                    <th>Mode of Payment</th>
                    <th>Rank</th>
                    <th>Course</th>
                    {{-- <th>Remarks</th> --}}
                    <th>Total Lodging Rate</th>
                    <th>Total Food Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weeklydatatable as $data)
                    <tr>
                        <td>{{ $data->roomtype }}</td>
                        <td>{{ $data->roomname }}</td>
                        <td>{{ $data->l_name }} {{ $data->f_name }}</td>
                        <td>{{ $data->datefrom }} - <br> {{ $data->dateto }}</td>
                        <td>{{ $data->checkindate }}</td>
                        <td>{{ $data->checkoutdate }}</td>
                        <td>{{ $data->company }}</td>
                        <td>{{ $data->paymentmode }}</td>
                        <td>{{ $data->rank }}</td>
                        <td>{{ $data->coursename }}</td>
                        @php
                        $datefromweekly = new DateTimeImmutable($data->checkindate);
                        $datetoweekly = new DateTimeImmutable($data->checkoutdate);
                        $counteddays = 0;
                    
                            while ($datefromweekly <= $datetoweekly) {
                                $datefromweekly = $datefromweekly->modify('+1 day'); // Increment the date by one day
                                $counteddays++;
                            }
                        @endphp
                        <td> USD {{ $data->NonNykRoomPrice * $counteddays }}</td>
                        <td> USD {{ $data->NonNykMealPrice * $counteddays }}</td>

                        <div style="display: none;">
                            {{ $totalroomrate += $data->NonNykRoomPrice * $counteddays }}
                            {{ $totalmealrate += $data->NonNykMealPrice * $counteddays }}
                        </div>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: .5em;">
            <p style="font-size: 11px; font-weight: bold;"> Total Lodging Rate: USD {{ $totalroomrate }} <br>
            Total Meal Rate: USD {{ $totalmealrate }} <br>
            Overall Total: USD {{ $total = $totalroomrate + $totalmealrate }} </p>
        </div>
    @endif
</body>
</html>
