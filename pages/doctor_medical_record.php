<?php
    date_default_timezone_set('Australia/Melbourne');
    require_once('session.php');

    $rid = isset($_GET['rid'])?$_GET['rid']:0;
   
     $pid = isset($_GET['pid'])?$_GET['pid']:0;
     $pid2 = 0;
$getid=isset($_POST['selPatient'])?$_POST['selPatient']:0;
     $person2 = explode("+",$getid);
if(count($person2) == 2)
{
$pid2= $person2[0];
}
if ($pid2 == 0)
{
$pid2= $pid;
}
$pname = isset($_GET['pname'])? $_GET['pname']: "";
    $post = isset($_GET['post'])? $_GET['post']:"";
    $person = array();
    
    if(isset($_POST['txtDoctorName']))
    {
        $name = $_POST['txtDoctorName'];
        $id = $_POST['txtDoctorId'];
        $type = $_POST['txtDoctorType'];
    }
    
    require_once("class.medical_record.php");
    require_once("class.person.php");
    require_once("class.validator.php");  


    $monitoring = 0;
	$emailsent = "";
	if (isset($_GET['changem']))
	{
		$changem = $_GET['changem'];
		$objPerson = new Person();
        $arPatient = $objPerson->ChangeMonitoringStatus($pid,$changem);
		if ($changem == 0)
		{
$mailmsg = "I got enough data, please stop monitoring";
$emailsent = "Mail sent to stop monitoring";
}
else
			{
$mailmsg = "Please start  monitoring";
$emailsent = "Mail sent to start  monitoring";
}		// get patient details
			$patientdetails = $objPerson->getMyDetails($pid);
			$email = $patientdetails['emailAddress'];
			$patientname = $patientdetails['firstName'];
			// get doctor details
			$doctordetails = $objPerson->getMyDoctor($pid);
			$phonenumber = $doctordetails['phoneNumber'];
			$doctorname= $doctordetails['firstName'] . " " .$doctordetails['lastName'] ;
			// get patient last record
			$objMedicalRecord = new MedicalRecord($pid);
			$patientRecord = $objMedicalRecord->getLastMedicalRecord();
			$tmp = $patientRecord['temperature'];
			$bp = $patientRecord['bp'];
			$hb = $patientRecord['hb'];
			$recorddate = $patientRecord['dateTime'];
			
			// prepare mail
			$to = $email;
			$subject = "HMA result";
			$message = "Hello $patientname, <br> $mailmsg <br><br>Kind Regards, <br> $doctorname";
			$from = "admin@hma.com.au";
			$headers = "From:" . $from;
			//mail($to,$subject,$message,$headers);
			//$emailsent = "Mail Sent to $patientname " ;
		
	}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <title>Healthcare support system::Doctor's medical record page</title>
        <link rel = "stylesheet" type = "text/css" href="../css/style_doctor.css" media="screen"></link>
        <style type = "text/css">
            .record_summary,.record_detail
            {
                position:relative;
                width:380px;
                border-style:dotted;
                border-color:#004382;
                border-width:1px;
                left:10px;
            }
            .record_detail
            {
                top:10px;
            }
        </style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script> 
<script type="text/javascript"> 
jQuery(document).ready(function($) {
i= 0;
$(function() {
  setInterval(function() {
var id = '<?php echo $pid2;?>';
var rid = '<?php echo $rid;?>';
$.get("doctor_auto_records.php?pid="+ id + "&rid=" + rid, function (data){
$(".sumresult").html(data);
});

  
  },1000)
});
});
</script> 

    </head>
    <body>
        <center>
            <?php
                include("../includes/header_sub.php");
                require_once("class.medical_record.php");
            ?>
            
            <div class = "content">
                <h1>Healthcare support system::Patients' medical record</h1>
                <p class="success"><?php echo "<b>$name</b>'s private page."?>
                <?php
                    if($post == "t")
                    {
                        echo "<p class = 'success'>You have successfully sent a message.</p>";
                    }
                    else if($post == "f")
                    {
                        echo "<p class = 'no_box_failure'>Message could not be sent. Please try again.</p>";   
                    }
                    
                ?>
                <div style = "margin:10px;">
                    <?php
                        if(isset($_POST['selPatient']))
                        {
                            $person = explode("+",$_POST['selPatient']);                         
                            if(count($person)==2)
                            {
                                $pid = $person[0];
                                $ar['personId'] =  $person[0];
                                                                
                                Validator::initialize($ar);
                                Validator::checkID();
                                                                
                                $success = $failure = array();
                                $failure = Validator::getFailureArray();
                                $success = Validator::getSuccessArray();
                                
                                if(isset($failure['personIdF']))
                                {
                                    echo "<p class ='failure' style = 'position:relative;left:-10px;width:290px!important;'>Please select a patient before continuing.</p>";
                                }
                                
                                if(isset($success['personIdS']))
                                {
                                    $pid = $success['personIdS'];
                                }
                                
                            }
                        }
                        $objPerson = new Person();
                        $arPatient = $objPerson->getMyPatients($id);
                        
                        if(count($arPatient))
                        {
                            echo "<form name = 'frmViewRecord' method = 'post' action = '",$_SERVER['PHP_SELF'],"'>";
                            echo "<b>Select a patient:&nbsp;&nbsp;&nbsp;&nbsp;</b>";
                            echo "<select name = 'selPatient'>";
                            echo "<option value = '0+ '>Select a patient</option>";
                            
                            for ($i = 0; $i < count($arPatient); ++$i)
                            {
                                $row = $arPatient[$i];
                                if(isset($_POST['selPatient']))
                                {
                                    if($pid == $row['personId'])
                                    {
                                        $pname = $row['firstName']." ".$row['lastName'];
										$monitoring = $row["monitoring"];
                                        echo "<option selected value = '",$row['personId'],"+",$row['firstName']." ".$row['lastName'],"'>",$row['firstName']." ".$row['lastName'],"</option>";
                                    }
                                    else
                                    {
                                        echo "<option value = '",$row['personId'],"+",$row['firstName']." ".$row['lastName'],"'>",$row['firstName']." ".$row['lastName'],"</option>";
                                    }
                                }
                                else if(isset($_GET['pid']))
                                {
                                    if($pid == $row['personId'])
                                    {
                                        $pname = $row['firstName']." ".$row['lastName'];
										$monitoring = $row["monitoring"];
                                        echo "<option selected value = '",$row['personId'],"+",$row['firstName']." ".$row['lastName'],"'>",$row['firstName']." ".$row['lastName'],"</option>";
                                    }
                                    else
                                    {
                                        echo "<option value = '",$row['personId'],"+",$row['firstName']." ".$row['lastName'],"'>",$row['firstName']." ".$row['lastName'],"</option>";  
                                    }
                                }
                                else
                                {
                                    echo "<option value = '",$row['personId'],"+",$row['firstName']." ".$row['lastName'],"'>",$row['firstName']." ".$row['lastName'],"</option>";     
                                }
                            }
                            echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;";
                            echo "<input type = 'submit' name = 'btnSubmit' value = 'View'/>";
                            echo "<input name='txtDoctorName' type = 'hidden' value = '$name'/>";
                            echo "<input name='txtDoctorId' type = 'hidden' value = '$id'/>";
                            echo "<input name='txtDoctorType'type = 'hidden' value = '$type'/>";
                            echo "</form>";
                        }
                        else
                        {
                            echo "<p class ='failure' style = 'position:relative;left:-10px;width:290px!important;'>No patient available.</p>";
                        }
                    ?>
                </div>
                
                <div class = "logout">
                    <p>
                        <a href = "activities.php">Back</a>&nbsp;|&nbsp;
                        <a href = "../index.php?logout=t">Logout</a>
                    </p>
                </div>
                
                <?php
                    if($pid > 0)
                    {
						echo "<div class = 'record_summary'><p>";
						if ($monitoring == 1)
						{
							echo "The patient is now monitoring <button onclick=location.href='?pid=$pid&changem=0'>Stop</button> $emailsent ";
						}
						else
						{
							echo "The patient is not monitoring <button onclick=location.href='?pid=$pid&changem=1'>Start</button> $emailsent";
						}
						echo "</p></div><br>";
		echo "<div class='sumresult'><span style='padding-left:20px; font-weight:bold;'>Loading Data</span></div>";			
                                           }
                ?>
            </div>
            <?php
                include("../includes/footer_sub.php");
            ?>
        </center>
    </body>
</html>
