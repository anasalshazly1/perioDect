<?php

REQUIRE_ONCE ("DBconnect.php");
session_start();
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
    public $CAL;
    public $BONELOSS;
    public $TEETHLOSS;
    public $PD;
    
    

    public function Addpatient(){
      $db = dbconnect::getInstance();
      $mysqli = $db->getConnection();
      $query = "INSERT INTO patient (`email`,`fname`,`lname`,`ut_id`,`age`,`phonenumber`)
      VALUES ('$this->email','$this->fname','$this->lname','2','$this->age','$this->phonenumber')";
      $result= $mysqli->query($query);
      if ($mysqli->query($query) === TRUE) {
        $last_id = $mysqli->insert_id;
        $_SESSION['patientid'] = $last_id;
        echo "New record created successfully. Last inserted ID is: " . $last_id;
      } else {
        echo "Error: " . $query . "<br>" . $mysqli->error;
      }
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
        $this->PatientID = $_SESSION['patientid'];
        $query = "INSERT INTO image (`ImageDate`,`imgPath`,`PatientID`)
        VALUES ('$this->ImageDate','$this->imgpath','$this->PatientID')";
        $result= $mysqli->query($query);
        $command = escapeshellcmd('C:\xampp\htdocs\perioDect\api\main.py images');
        $output = shell_exec($command);
        echo $output;
        return $result;
      }

    public function CheckMeasurements(){
      $db = dbconnect::getInstance();
      $mysqli = $db->getConnection();
      $query = "INSERT INTO measurements(`cal`,`boneloss`,`teethloss`,`pd`)
      VALUES ('$this->CAL','$this->BONELOSS','$this->TEETHLOSS','$this->PD')";
      $result= $mysqli->query($query);
      return $result;
    }




    

}



?>
