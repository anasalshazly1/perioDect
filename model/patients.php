<?php

REQUIRE_ONCE ("DBconnect.php");
class patients{
    public $id;
    public $fname;
    public $lname;
    public $email;
    public $age;
    public $phonenumber;
    public $pass;
    public $ut_id;
    public $image;
    public $page;
    public $pat_id;
    public $numberVisits;

    public $imageid;
    public $imgpath;
    public $ImageDate;
    public $PatientID;
    
    

    public function Addpatient(){
      $db = dbconnect::getInstance();
      $mysqli = $db->getConnection();
      $query = "INSERT INTO patient (`email`,`fname`,`lname`,`age`,`phonenumber`,`ut_id`)
      VALUES ('$this->email','$this->fname','$this->lname','$this->age','$this->phonenumber','2')";
      $result= $mysqli->query($query);
      return $result;
    }

    public function AddtoUser(){
        $db = dbconnect::getInstance();
        $mysqli = $db->getConnection();
        $query = "INSERT INTO users (`email`,`fname`,`lname`,`ut_id`,`page`)
        VALUES ('$this->email','$this->fname','$this->lname','2','/doctor/doctorPatientlist.php')";
        $result= $mysqli->query($query);
        return $result;
      }



    public function UploadPicture(){
        $db = dbconnect::getInstance();
        $mysqli = $db->getConnection();
        
        $query = "INSERT INTO image (`ImageDate`,`imgPath`,`PatientID`)
        VALUES ('$this->ImageDate','$this->imgpath','$this->PatientID')";
        $result= $mysqli->query($query);
        $command = escapeshellcmd('C:\xampp\htdocs\perioDect\api\main.py images');
        $output = shell_exec($command);
        echo $output;
        return $result;
      }




    

}



?>
