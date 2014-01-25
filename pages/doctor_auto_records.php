<?php 
    $rid = isset($_GET['rid'])?$_GET['rid']:0;
    $pid = isset($_GET['pid'])?$_GET['pid']:0;
    $pname = isset($_GET['pname'])? $_GET['pname']: "";   
    require_once("class.medical_record.php");
    require_once("class.person.php");
    require_once("class.validator.php");  


 echo "<div class = 'record_summary'><p>";
                        $objMedicalRecord = new MedicalRecord($pid);
                        $arMedicalRecord = $objMedicalRecord->getMedicalRecord();
                        
                        if(count($arMedicalRecord))
                        {
                            for($i = 0; $i < count($arMedicalRecord); ++$i)
                            {
                                $row = $arMedicalRecord[$i];
                                if($i == $rid)
                                {
                                    echo date_format(new DateTime($row['datetime']),"Y M d"),"<br/>";
                                }
                                else
                                {                         
                                    echo "<a href ='doctor_medical_record.php?pid=$pid&rid=$i&pname=$pname&datetime=",$row['datetime'],"'>",date_format(new DateTime($row['datetime']),"Y M d"),"</a><br/>";
                                }
                            }
                        }
                        else
                        {
                            echo "No medical record available.";
                        }
                        echo "</p></div>";
                        echo "<div class = 'record_detail'>";
                        
                        if(count($arMedicalRecord))
                        {
                            $datetimeRow = isset($arMedicalRecord[$rid])? $arMedicalRecord[$rid]: null;
                            
                            if(is_array($datetimeRow))
                            {
                                $datetime = $datetimeRow['datetime'];
                                
                                $arMedicalRecordDetails = $objMedicalRecord->getMedicalRecordDetails($pid,$datetime);
                                $strDateTime = $strTemperature = $strAskDoctor = $strbp = $strhb = "";
                                for($i = 0; $i < count($arMedicalRecordDetails); ++$i)
                                {
                                    $row = $arMedicalRecordDetails[$i];
                                    $strDateTime = $strDateTime.date_format(new DateTime($row['datetime']),"h:i:s A")."<br/>";
                                    $strTemperature = $strTemperature.number_format($row['temperature'],2)."<br/>";
									$strbp = $strbp.$row['bp']."<br/>";
									$strhb = $strhb.$row['hb']."<br/>";
                                    $strAskDoctor = $strAskDoctor."<a href='write-comment.php?pname=$pname&pid=$pid&mid={$row['recordId']}'>Write comment</a><br/>";
                                }
                                echo "<div><h2>Details on ",date_format(new DateTime($datetime),"Y M d"),"</h2></div>";
                                echo "<div><p><b>Time</b><b style = 'position:relative;left:60px;'>Temperature</b>
								<b style = 'position:relative;left:80px;'>BP</b>
								<b style = 'position:relative;left:100px;'>HB</b>
								<b style = 'position:relative;left:130px;'>Action</b></p></div>";


echo "<div>";
echo "<p>$strDateTime</p>";
echo "<p style='position:absolute;left:90px;top:58px;'>$strTemperature</p>";
echo "<p style='position:absolute;left:190px;top:58px;'>$strbp</p>";
echo "<p style='position:absolute;left:230px;top:58px;'>$strhb</p>";
echo "<p style='position:absolute;left:270px;top:58px;'>$strAskDoctor</p>";
echo "</div>";
}

}
else
{
echo "<div><p>no medical record details available.</p></div>";

}
echo "</div><br/>";


?>
