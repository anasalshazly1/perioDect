<?php
REQUIRE_ONCE "../model/patients.php";
REQUIRE_ONCE "../view/view.php";
session_start();
class patientcontroller{


    public function add(){
        $patient = new patients;
		$patient->fname= $_REQUEST['name'];
		$patient->lname= $_REQUEST['lname'];
		$patient->email= $_REQUEST['email'];
		$patient->age= $_REQUEST['age'];
        $patient->phonenum= $_REQUEST['phonenumber'];

          $patient->Addpatient();
          $patient->AddtoUser();

    }

    public function upload(){
        $patient = new patients;
        $photo = $_FILES['imageupload']['name'];
		$patient->ImageDate= date('Y-m-d');
		$patient->PatientID= $_REQUEST['password'];
        move_uploaded_file($_FILES['imageupload']['tmp_name'], '../images/'.$photo);
		$patient->imgpath = $photo;

        $patient->UploadPicture();
            
    }

    

}
$cont= new patientcontroller;

if($_GET['action']=='upload')
{
    $cont->upload();
    header("location: ../doctor/picturetesting.php");
}

if($_GET['action']=='add')
{
    $cont->add();
    header("location: ../doctor/picturetesting.php");
}



?>