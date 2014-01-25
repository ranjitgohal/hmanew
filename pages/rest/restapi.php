<<<<<<< HEAD
<?php
	
	require_once("Rest.inc.php");
	require_once("../class.database.php");
	
	class RESTws extends REST {
		public function __construct()
		{
			parent::__construct();
		}

		// Public method for access web service.
		public function processWebService(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404); // If the method not exist with in this class, response would be "Page not found".
		}
	 
		// login method
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$email = $this->_request['email'];			
			$password = $this->_request['pwd'];

			// validate email and password
			if(!empty($email) and !empty($password))
			{
				if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$sql = "SELECT CONCAT_WS(' ','Name:',firstName, lastName) AS name, CONCAT('Sex: ',sex) AS sex, ";
					$sql = $sql."CONCAT('Age: ',YEAR(CURRENT_DATE) - YEAR(dob)) AS age, personId FROM person WHERE ";
					$sql = $sql."emailAddress = '$email' AND password = '".md5($password)."' AND personType = 'p' AND accepted = 'a'";
					$result = Database::getInstance()->getRow($sql);
					if(is_array($result)){
						$this->response($this->json($result), 200);
					}
					else
					{
						$error = array('status' => "Failed", "msg" => "Invalid Email or Password");
						$this->response($this->json($error), 400);
					}
				}
			}
			
			$error = array('status' => "Failed", "msg" => "You must enter your email and password");
			$this->response($this->json($error), 400);
		}
		
		// master
		// record parameters for patient
		// instance A
		public function record()
		{
			if($this->get_request_method() == "GET")
			{
				$id = $this->_request['id'];			
				$tmp = $this->_request['tmp'];
				$bp = $this->_request['bp'];
				$hb = $this->_request['hb'];
				
				if(!empty($id) and !empty($tmp) and !empty($bp) and !empty($hb))
				{
					$sql = "select monitoring from person where personid=$id";
					$patientrow = Database::getInstance()->getRow($sql);
					
					$monitoring = $patientrow["monitoring"];
					
					if ($monitoring == 1 )
					{
						$checked = $this->checkrecord($id);
						if ($checked == false)
						{
							$sql = "INSERT INTO medicalRecord(personId,dateTime,temperature)VALUES($id,now(),$tmp)";
							$result = Database::getInstance()->insertRow($sql);
						
							$this->instanceB($result,$bp,$hb);
							
							if($result != -1){
								$success = array('status' => "Success", "msg" => "Parameters has been successfully recorded.");
								$this->response($this->json($success), 200);
							}
							else
							{
								$error = array('status' => "Failed", "msg" => "An error occured while updating the database.");
								$this->response($this->json($error), 400);
							}
						}
						else
						{
							$error = array('status' => "Failed", "msg" => "You can only check every 3 seconds");
							$this->response($this->json($error), 406);
						}	
					}
					else
					{
						$error = array('status' => "Failed", "msg" => "Patient is currently not monitored, Doctor need to change status");
						$this->response($this->json($error), 406);
					}
				}
				else
				{
					$error = array('status' => "Failed", "msg" => "parameters can't be empty!");
					$this->response($this->json($error), 400);
				}
			}
			else
			{
				$this->response('',406);
			}
		}
		
		// slave
		// instance B
		private function instanceB($id,$bp,$hb)
		{
			$sql = "update medicalRecord set hb='$hb', bp= '$bp' where recordid=$id";
			$result = Database::getInstance()->update($sql);
		}
		
		private function json($data)
		{
			if(is_array($data))
			{
				return json_encode($data);
			}
		}
		
		
		private function checkrecord($id)
		{
			$sql = "SELECT dateTime FROM medicalRecord where personid=$id order by recordid desc limit 1";
			$recordrow = Database::getInstance()->getRow($sql);
			
			$dateTime = $recordrow["dateTime"];
			$checked = 0;
			if ($dateTime != "")
			{
				$tz_object = new DateTimeZone('Australia/Melbourne');
				$date11 = new DateTime();
				$date11->setTimezone($tz_object); 
				$date22 = new DateTime($dateTime);

				$date1=date_create($date11->format("Y-m-d H:i:s"));
				$date2=date_create($date22->format("Y-m-d H:i:s"));
				$diff=date_diff($date1,$date2);

				if ($diff->format("%Y-%m-%d") == "00-0-0")
				{
					if ($diff->format("%H") == "0")
					{
						if ($diff->format("%I") == "0")
						{
							if ($diff->format("%S") < 3)
							{
								$checked = 1;
							}
						}
					}
				}
			}
			
			return$checked;
		}
	}
	
	$restws = new RESTws;
	$restws->processWebService();
?>
=======
<?php
	
	require_once("Rest.inc.php");
	require_once("../class.database.php");
	
	class RESTws extends REST {
		public function __construct()
		{
			parent::__construct();
		}

		// Public method for access web service.
		public function processWebService(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404); // If the method not exist with in this class, response would be "Page not found".
		}
	 
		// login method
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$email = $this->_request['email'];			
			$password = $this->_request['pwd'];

			// validate email and password
			if(!empty($email) and !empty($password))
			{
				if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$sql = "SELECT CONCAT_WS(' ','Name:',firstName, lastName) AS name, CONCAT('Sex: ',sex) AS sex, ";
					$sql = $sql."CONCAT('Age: ',YEAR(CURRENT_DATE) - YEAR(dob)) AS age, personId FROM person WHERE ";
					$sql = $sql."emailAddress = '$email' AND password = '".md5($password)."' AND personType = 'p' AND accepted = 'a'";
					$result = Database::getInstance()->getRow($sql);
					if(is_array($result)){
						$this->response($this->json($result), 200);
					}
					else
					{
						$error = array('status' => "Failed", "msg" => "Invalid Email or Password");
						$this->response($this->json($error), 400);
					}
				}
			}
			
			$error = array('status' => "Failed", "msg" => "You must enter your email and password");
			$this->response($this->json($error), 400);
		}
		
		// master
		// record parameters for patient
		// instance A
		public function record()
		{
			
				$id = $this->_request['id'];			
				$tmp = $this->_request['tmp'];
				$bp = $this->_request['bp'];
				$hb = $this->_request['hb'];
				
				if(!empty($id) and !empty($tmp) and !empty($bp) and !empty($hb))
				{

					$sql = "select *  from slaves  where patientid=$id";
					$patientrow = Database::getInstance()->getRow($sql);
	$mypatientid = $patientrow["patientid"];
	$myslaveid = $patientrow["slaveid"];
	$slavemsg ="";
	if ($mypatientid != '')
	{


					$sql = "select monitoring from person where personid=$id";
					$patientrow = Database::getInstance()->getRow($sql);
					
					$monitoring = $patientrow["monitoring"];
					
					if ($monitoring == 1 )
					{
						$checked = $this->checkrecord($id);
						if ($checked == false)
						{
							$sql = "INSERT INTO medicalRecord(personId,dateTime,temperature,hb,bp)VALUES($id,now(),$tmp,$hb,$bp)";
							$result = Database::getInstance()->insertRow($sql);
					if($myslaveid != 0)
{	
							$this->instanceB($result,$bp,$hb);
}	
else
{
$slavemsg = " +++ Slave ID doesnt exist!";
}						
							if($result != -1){
								$success = array('status' => "Success", "msg" => "Parameters has been successfully recorded. $slavemsg ");
								$this->response($this->json($success), 200);
							}
							else
							{
								$error = array('status' => "Failed", "msg" => "An error occured while updating the database.");
								$this->response($this->json($error), 400);
							}
						}
						else
						{
							$error = array('status' => "Failed", "msg" => "You can only check every 3 seconds");
							$this->response($this->json($error), 406);
						}	
					}
					else
					{
						$error = array('status' => "Failed", "msg" => "Patient is currently not monitored, Doctor need to change status");
						$this->response($this->json($error), 406);
					}
	}
				else
				{
					$error = array('status' => "Failed", "msg" => "Patient ID does not exist!");
					$this->response($this->json($error), 400);
				}

				}
				else
				{
					$error = array('status' => "Failed", "msg" => "parameters can't be empty!");
					$this->response($this->json($error), 400);
				}
			
		}
		
		// slave
		// instance B
		private function instanceB($id,$bp,$hb)
		{
			
                  $url="http://118.138.241.174/hmaslave/pages/rest/record?id=36&tmp=0&bp=$bp&hb=$hb";
$input = file_get_contents($url);

}
private function json ($data)
		{
			if(is_array($data))
			{
				return json_encode($data);
			}
		}
		
		
		private function checkrecord($id)
		{
			$sql = "SELECT dateTime FROM medicalRecord where personid=$id order by recordid desc limit 1";
			$recordrow = Database::getInstance()->getRow($sql);
			
			$dateTime = $recordrow["dateTime"];
			$checked = 0;
			if ($dateTime != "")
			{
				$tz_object = new DateTimeZone('Australia/Melbourne');
				$date11 = new DateTime();
				$date11->setTimezone($tz_object); 
				$date22 = new DateTime($dateTime);

				$date1=date_create($date11->format("Y-m-d H:i:s"));
				$date2=date_create($date22->format("Y-m-d H:i:s"));
				$diff=date_diff($date1,$date2);

				if ($diff->format("%Y-%m-%d") == "00-0-0")
				{
					if ($diff->format("%H") == "0")
					{
						if ($diff->format("%I") == "0")
						{
							if ($diff->format("%S") < 3)
							{
								$checked = 1;
							}
						}
					}
				}
			}
			
			return$checked;
		}
	}
	
	$restws = new RESTws;
	$restws->processWebService();
?>
>>>>>>> eb58be41f1f8a3c8295a18fb3d4a65c19e14a052
