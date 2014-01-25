<?php
require_once("class.database.php");

class medicalRecord
{
    private $_id;
    
    public function __construct($id)
    {
        $this->_id = $id;
    }
    
    public function getmedicalRecord()
    {
        $sql = "SELECT DISTINCT DATE(datetime) AS datetime FROM medicalRecord WHERE personId = {$this->_id} ORDER BY datetime DESC";
        return Database::getInstance()->getTable($sql);
    }
    
    public function getmedicalRecordDetails($id,$date)
    {
        $sql = "SELECT recordId, TIME(datetime) AS datetime, temperature, bp,hb FROM medicalRecord WHERE personId = $id ";
        $sql = $sql."AND DATE(datetime) = '$date' ORDER BY datetime DESC";
        return Database::getInstance()->getTable($sql);
    }
	
	 public function getLastmedicalRecord()
    {
        $sql = "SELECT * FROM medicalRecord WHERE personId = {$this->_id} ORDER BY datetime DESC limit 1";
        return Database::getInstance()->getRow($sql); 
    }
}
?>