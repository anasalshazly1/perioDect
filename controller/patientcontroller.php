<?php
REQUIRE_ONCE "../model/patients.php";
REQUIRE_ONCE "../view/view.php";
class patientcontroller{


    public function add(){
        $patient = new patients;
        $patient->email= $_REQUEST['email'];
		$patient->fname= $_REQUEST['name'];
		$patient->lname= $_REQUEST['lname'];
		$patient->age= $_REQUEST['age'];
        $patient->phonenumber= $_REQUEST['phonenumber'];
        

        $patient->Addpatient();
        $patient->AddtoUser();

    }

    public function upload(){
        $patient = new patients;
        // $patient->PatientID= $_SESSION['patientid'];
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

    public function check(){
        
        $patient = new patients;
        // $CAL = $_FILES['numberFormat']['id'];
        // $BONELOSS = $_FILES['numberFormat']['id'];
        // $TEETHLOSS = $_FILES['numberFormat']['id'];
        // $PD = $_FILES['numberFormat']['id'];
        // $patient->ImageID= $_REQUEST['ImageID'];
		$patient->PatientID= $_REQUEST['patientid'];
        $patient->CAL= $_REQUEST['CAL'];
		$patient->BONELOSS= $_REQUEST['BL'];
		$patient->TEETHLOSS= $_REQUEST['TL'];
		$patient->PD= $_REQUEST['PD'];
        $output = shell_exec("conda activate base2 & python ../api/fuzzy.py $patient->CAL $patient->BONELOSS $patient->TEETHLOSS $patient->PD 2>&1");
        $output = explode('--Results--',$output)[1];
        $output = trim($output);
        $output = preg_split('/\s|\r\n|\n|\n\r/',$output);
        $keys = array_filter($output,function($key){ return $key % 2 == 0; },ARRAY_FILTER_USE_KEY);
        $values = array_filter($output,function($key){ return $key % 2 == 1; },ARRAY_FILTER_USE_KEY);
        $values = array_merge($values);
        $keys = array_merge($keys);
        
        $results = [];
        foreach($keys as $key=>$value){
            $value = trim($value,":");
            $results[$value] = $values[$key];
        }
        $_SESSION["severity_results"] = $results;
        $patient->CheckMeasurements();
        
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
    header("location: ../doctor/picturetesting.php?result = ".$_SESSION['patientid']."");
}

if($_GET['action']=='check')
{
    $cont->check();
    header("location: ../doctor/severity_check.php?result = ".$_SESSION['patientid']."");
}


?>