<?php
require_once('class.web_service.php');

$objWebService = new WebService();
echo $objWebService->authenticateUser("test3@test.com", "121212");

?>