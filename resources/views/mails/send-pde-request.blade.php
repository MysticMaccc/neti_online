<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Century Gothic;
        }
        table {
            border-collapse: collapse;
            font-size: 12px;
            color: #273c75;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #273c75;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <p style="color:#273c75;font-family:Century Gothic">Hi Ms Grace,</p>
    <p style="color:#273c75;font-family:Century Gothic">Good day!</p>
    <p style="color:#273c75;font-family:Century Gothic">
        Please see new PDE request below:
    </p>
    <table>
        <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Vessel</th>
            <th>Requested By</th>
        </tr>
        <tr>
            @foreach ($emailContent as $row)
            <tr>
                <!-- Populate table rows with data -->
                <td>{{ $row['Fullname'] }}</td>
                <td>{{ $row['Position'] }}</td>
                <td>{{ $row['Vessel'] }}</td>
                <td>{{ $row['Requested By'] }}</td>
            
          
            </tr>
        @endforeach
        </tr>
    </table>
    <br>
    <p style="color:#273c75;font-family:Century Gothic">
        To view the request details kindly login to OESX System, click <a href="https://netionline.neti.com.ph/enrollment-system">here</a>.
    </p>
    <br>
    <p style="color:#273c75;font-family:Century Gothic">Thank you and God Bless</p>
    <p style="color:#273c75;font-family:Century Gothic">
        OESX SYSTEM
        <br>---------------------------------------------------------------------------
        <br>NYK-FIL MARITIME E-TRAINING INC.
        <br>NYK-TDG I.T. Park, Knowledge Ave, Carmeltown
        <br>Canlubang, Calamba, Laguna, Philippines, 4028
        <br>************************************************************
        <br>NYK Group Values: Integrity, Innovation, Intensity
        <br>************************************************************
        <br>This is a system generated message. Please do not reply.
    </p>
</body>
</html>
