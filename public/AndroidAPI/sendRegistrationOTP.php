<?php
include ('dbcon.php');
// include ('SMTPSettings.php');
include ('SendEmailFunction.php');

$mobilenumber = (empty($_POST["mobilenumber"])) ? "":$_POST["mobilenumber"];
$email = (empty($_POST["email"])) ?"":$_POST["email"];
$verificationcode = (empty($_POST["verificationcode"])) ?"":$_POST["verificationcode"];

// $mobilenumber = "09509098702";
// $email = "sherwin.roxas@neti.com.ph";
// $verificationcode = "0001112";



ob_start();
$html = include 'RegistrationOtpEmailFormat.php';
$content = ob_get_clean();

//echo $content;

//send email
sendmail($content,$email,"Verification Code");




// ----------------------------------------SMS-----------------------------------------
// ----------------------------------------SMS-----------------------------------------
// ----------------------------------------SMS-----------------------------------------
// ----------------------------------------SMS-----------------------------------------

              $tar_msg = " Welcome to NETI Enrollment System. Your verification code is: ".$verificationcode.". Please enter this code on the verification page to complete the process. Do not share this OTP with anyone.";
              $tar_mode = "text";
              $label = "";
              $priority = "1";
              $clientid = "";
              
              $username = "phdemosmsapi";
              $password = "Mwt4nq8hr3kl2";
              $callerid = "TXMSG";
              $route_to = "api_send_sms";

              if (substr($mobilenumber, 0, 1) === '0') {
                $tar_num = '63' . substr($mobilenumber, 1);
              }
              else{
                $tar_num = $mobilenumber;
              }

              
                 
            // create a new cURL resource
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,str_replace(" ","%20",  "https://www.sendquickasp.com/client_api/index.php?route_to=".$route_to."&username=".$username."&passwd=".$password."&tar_num=".$tar_num."&tar_msg=".$tar_msg."&callerid=".$callerid));
            curl_setopt($ch, CURLOPT_HEADER, 0);
    
            // grab URL and pass it to the browser
            curl_exec($ch);

            // close cURL resource, and free up system resources
            curl_close($ch);
             

?>