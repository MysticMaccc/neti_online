<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Approved</title>
    <style>
        /* Add your email styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Center the container vertically */
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #093b72;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body style="background-color: #f5f5f5; margin: 0; padding: 0; background-image: url('https://netionline.neti.com.ph/assets/images/oesximg/card.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="container" style="margin-top: 10px;">
        <div style="text-align: center;">
        <img src="https://netionline.neti.com.ph/assets/images/oesximg/logo.png" width="150" height="auto" alt="">
        </div>
        

        {{-- contents --}}
        <p>Hi {{$name}},</p>

        <p>This is to remind you of your upcoming training:</p>

        <p>Training: {{$course}}<br>
            Date: {{$trainingdate}}<br><br>
            
            Attire:<br>
            For online session : NETI training shirt or any polo shirt<br>
            For on-site session: NETI training shirt or any polo shirt, dark pants and black shoes<br>
            Reminder: Collarless shirt, maong pants, rubber shoes and slippers are NOT ALLOWED</p>

        <p>Don’t forget to upload the ff. requirements at your NETI online account.<br>
            1. Valid Basic PEME Medical Certificate<br>
            2. COP of BT<br>
            3. Proof of payment</p>

        <p>MAKE SURE TO CLICK AN OPTION BEFORE CLOSING THIS MESSAGE</p>

        <a href="https://netionline.neti.com.ph/recordConfirmation/{{$enroledid}}/1" 
        style="display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; border-radius: 20px;">
                I will attend the training
        </a>

        <a href="https://netionline.neti.com.ph/recordConfirmation/{{$enroledid}}/0" 
        style="display: inline-block; padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 20px;">
            I will NOT attend the training
        </a>

    </div>
</body>
</html>