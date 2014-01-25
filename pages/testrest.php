<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <title>Healthcare support system</title>
        <link rel = "stylesheet" type = "text/css" href="css/style_home.css" media="screen"></link>
    </head>
    <body>
<?php

/** convert json to array
$input = file_get_contents($url);
$jsonData = json_decode($input, true);   
$output = $jsonData['phoneNumber'];
echo $output;
*/
?>
        <center>
		<br><br>
			Check Adding Parameter with Webservice using POST<br><br>
			<form name = "frmSendData" method = "post" action = "rest/record">
				<?php 
				require_once("class.person.php");
				$objPerson = new Person();
                $arPatient = $objPerson->getMyPatients(35);

				
				echo "<b>Select a patient:&nbsp;&nbsp;&nbsp;&nbsp;</b>";
				echo "<select name = 'id'>";
			
				for ($i = 0; $i < count($arPatient); ++$i)
				{
					$row = $arPatient[$i];
					echo "<option value = '",$row['personId'],"'>",$row['firstName']." ".$row['lastName'],"</option>";     
				}
				echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;";

				?>
				
				<b>Tmp </b>
				<input class = "input" type = "text" name = "tmp" size = "5" value="37" /> 
				<b>bp</b>
				<input class ="input" type = "text" name = "bp" size = "5" value="56"/> 
				<b>hb</b>
				<input class ="input" type = "text" name = "hb" size = "5" value="33"/> <br/><br/>
				<div class= "login_button">
					<input type = "submit" name = "btnSubmit" value="Send"/>
				</div>
			</form>
					
			<br><br><br><br>
			
			Check Patient Login with Webservice using POST:<br><br>
		
            <div class = "content">
                    <form name = "frmLogin" method = "post" action = "rest/login">
                        <input type="hidden" name="inputLoginHidden"/>
                        <b>Username</b>
                        <input class = "input" type = "text" name = "email" size = "20"/> <br/><br/>
                        <b>Password</b>
                        <input class ="input" type = "password" name = "pwd" size = "20"/> <br/><br/>
                        <div class= "login_button">
                            <input type = "submit" name = "btnSubmit" value="submit"/>&nbsp;&nbsp;
                            <input type= "reset" name = "btnReset" value = "reset"/>
                        </div>
                    </form>
                    
                </div>
            </div>
        </center>
    </body>
</html>



