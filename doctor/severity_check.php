<?php
REQUIRE_ONCE "../view/view.php";
// session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <title>Measurements Form</title>
</head>
<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">PerioDect</a>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse ">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                    <li class="nav-item  mb-2">
                            <a class="nav-link  text-white" href="dentists.php">
                                <i class="fas fa-users"></i>
                                Dentists
                            </a>
                        </li>
                        <li class="nav-item  mb-2">
                            <a class="nav-link  text-white" href="reports.php">
                              <i class="fas fa-stethoscope"></i>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item  mb-2">
                            <a class="nav-link text-white" href="editaccount.php">
                             <i class="fa fa-user"></i>
                                Edit Account
                            </a>
                        </li>
                    </ul>
                    <div class="border-top border-light p-3 mb-4 mt-5">

                        <div class="text-center">
                            <a href="logout.php" class="btn btn-outline-danger">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1> Please enter the correct measurements to check severity </h1>
                </div>
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Tooth Selection
                    </button>
                    <div class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton"> 
                        <li class="dropdown-header">Upper Right</li>
                        <li><a class="dropdown-item" href="#">1</a></li>
                        <li><a class="dropdown-item" href="#">2</a></li>
                        <li><a class="dropdown-item" href="#">3</a></li>
                        <li><a class="dropdown-item" href="#">4</a></li>
                        <li><a class="dropdown-item" href="#">5</a></li>
                        <li><a class="dropdown-item" href="#">6</a></li>
                        <li><a class="dropdown-item" href="#">7</a></li>
                        <li><a class="dropdown-item" href="#">8</a></li>
                        <li class="dropdown-header">Upper Left</li>
                        <li><a class="dropdown-item" href="#">1</a></li>
                        <li><a class="dropdown-item" href="#">2</a></li>
                        <li><a class="dropdown-item" href="#">3</a></li>
                        <li><a class="dropdown-item" href="#">4</a></li>
                        <li><a class="dropdown-item" href="#">5</a></li>
                        <li><a class="dropdown-item" href="#">6</a></li>
                        <li><a class="dropdown-item" href="#">7</a></li>
                        <li><a class="dropdown-item" href="#">8</a></li>
                        <li class="dropdown-header">Bottom Right</li>
                        <li><a class="dropdown-item" href="#">1</a></li>
                        <li><a class="dropdown-item" href="#">2</a></li>
                        <li><a class="dropdown-item" href="#">3</a></li>
                        <li><a class="dropdown-item" href="#">4</a></li>
                        <li><a class="dropdown-item" href="#">5</a></li>
                        <li><a class="dropdown-item" href="#">6</a></li>
                        <li><a class="dropdown-item" href="#">7</a></li>
                        <li><a class="dropdown-item" href="#">8</a></li>
                        <li class="dropdown-header">Bottom Left</li>
                        <li><a class="dropdown-item" href="#">1</a></li>
                        <li><a class="dropdown-item" href="#">2</a></li>
                        <li><a class="dropdown-item" href="#">3</a></li>
                        <li><a class="dropdown-item" href="#">4</a></li>
                        <li><a class="dropdown-item" href="#">5</a></li>
                        <li><a class="dropdown-item" href="#">6</a></li>
                        <li><a class="dropdown-item" href="#">7</a></li>
                        <li><a class="dropdown-item" href="#">8</a></li>
                    </div>
                </div>
                <div class="col-md-10 col-lg-10 ml-auto">
                    <form id="register-form" method="POST" action="../controller/patientcontroller.php?action=check">
                        <div class="form-group col-lg-7 mb-2">
                            <input type="text" class="form-control" name="CAL" placeholder="Clinical Attachement Loss" required>
                        </div> 
                        <input type="hidden" class="form-control" name ="patientid" value="'.$_SESSION['patientid'].'">   
                        <div class="form-group col-lg-7 mb-2">
                            <input type="text" name="BL" class="form-control" placeholder="Bone Loss" required>
                        </div>
                        <div class="form-group col-lg-7 mb-2">
                            <input type="text" name="TL" class="form-control" placeholder="Teeth Loss" required>
                        </div>
                        <div class="form-group col-lg-7 mb-2">
                            <input type="text" class="form-control" name="PD" placeholder="Probing Depth" required>
                        </div>
                        <div class="form-group col-lg-7  mb-2">
                            <input type="submit" name="save" value="check" class="btn btn-dark btn-block py-2 font-weight-bold">
                        </div>
                    </form>
                    <?php
                            if(isset($_SESSION['severity_results'])){
                                foreach($_SESSION["severity_results"] as $key=>$result){
                                    ?>
                                    <p><?= ucwords($key).": ". $result; ?></p>
                                    <?php
                                }
                                ?>
                                <?php
                                unset($_SESSION['severity_results']);
                            }
                        ?>
                </div>
            </main>
        </div>
    </div>
</body>