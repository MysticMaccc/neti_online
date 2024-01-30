<?php
include ('dbcon.php');
$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];

$query = "select emailaddress from tbltraineeaccount 
where traineeid = ".$traineeid."  ";
        
$query_result = mysqli_query($con , $query );


        while($row = mysqli_fetch_array($query_result))
        {
            $Email = $row["emailaddress"];
            echo $Email;
        }

?>