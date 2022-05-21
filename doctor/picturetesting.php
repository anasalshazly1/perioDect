<!doctype html>
<html lang="en">
<?php
session_start();
REQUIRE_ONCE "../view/view.php";
?>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../admin.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Examination part 1</title>
</head>

<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Periodontal clinic</a>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse ">
                <div class="position-sticky pt-2" >
                    <ul class="nav flex-column">    
                        <li class="nav-item  mb-1">
                            <a class="nav-link  text-white" href="viewSchedule.php">
                                <i class="fas fa-users"></i>
                                Patients
                            </a>
                        </li>
                        <li class="nav-item  mb-2">
                            <a class="nav-link text-white" href="editaccountDR.php">
                                <i class="fas fa-key"></i>
                                View account
                            </a>
                        </li>
                    </ul>
                    
                    <div class="border-top border-light p-3 mb-4 mt-5">
                        <div class="text-center">
                            <a href="logoutadmin.php" class="btn btn-outline-danger">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-secondary">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Check Image</h1>
                </div>
                <div class="container col-md-6">
                    <div class="mb-5">
                        <?php
                            if(isset($_SESSION['result'])){
                                ?>
                                <p><?= $_SESSION["result"]; ?></p>
                                <?php
                                unset($_SESSION['result']);
                            }
                        ?>
                        <form action="../controller/patientcontroller.php?action=upload" method="post" enctype="multipart/form-data">
                            <input name="imageupload" class="form-control" type="file" id="formFile" onchange="preview()">
                            <!-- <button name="save" class="btn btn-primary mt-3">Check Image</button> -->
                            <input type="submit" name="save" value="Upload" class="btn btn-primary mt-3">
                        </form>
                        
                        <button onclick="window.location.href='severity_check.php'" class="btn btn-primary mt-3">Take measurement</button>
                    </div>
                    <img id="frame" src="" class="img-fluid" />
                </div>
            </main>
            <script>
                function preview() {
                    frame.src = URL.createObjectURL(event.target.files[0]);
                }
                function clearImage() {
                    document.getElementById('formFile').value = null;
                    frame.src = "";
                } 
            </script>
        </div>
    </div>
</body>