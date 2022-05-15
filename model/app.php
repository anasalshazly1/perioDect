<?php

REQUIRE_ONCE ("DBconnect.php");

class app{
    public $day;
    public $time;
    public $date;

    public function addSlots(){
        $db = dbconnect::getInstance();
        $mysqli = $db->getConnection();
        $query = "INSERT INTO timeslot (`id`,`day`,`time`,`date`) VALUES (null,'$this->day','$this->time','$this->date')";
        $result= $mysqli->query($query); 
        return $result;
    }
}