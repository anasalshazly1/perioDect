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
		// $patient->PatientID= $_REQUEST['password'];
        move_uploaded_file($_FILES['imageupload']['tmp_name'], '../images/'.$photo);
        $output = shell_exec("conda activate base2 & python ../api/main.py $photo 2>&1");
        $output = (preg_split('/\s|\r\n|\r|\n/',$output));
        $output = array_filter($output,function($value){
            return $value != "";
        });
       
		$patient->imgpath = $photo;
        
        $patient->UploadPicture();
        return end($output);
    }

    

}
$cont= new patientcontroller;

if($_GET['action']=='upload')
{
    $output = $cont->upload();
    $_SESSION["result"] = $output;
    header("location: ../doctor/picturetesting.php");
}

if($_GET['action']=='add')
{
    $cont->add();
    header("location: ../doctor/picturetesting.php");
}



?>