<!doctype html>
<html lang="en">
<?php
REQUIRE_ONCE "../view/view.php";
?>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="admin.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <title>Add Patient</title>
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Periodontal clinic</a>
    </header>
    <div class="container-fluid">
        <div class="row">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1> Add Patient </h1>
    </div>
    <div class="col-md-10 col-lg-10 ml-auto">
        <!-- Registeration Form -->
        <form id="register-form" method="POST" action="controller/usercontroller.php?action=add">
            <div class="form-group col-lg-7 mb-2">
                <input type="text" name="name" class="form-control" placeholder="First Name" required>
            </div>
            <div class="form-group col-lg-7 mb-2">
                <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
            </div>
            <div class="form-group col-lg-7 mb-2">
                <input type="email" class="form-control" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group col-lg-7 mb-2">
                <input type="number" class="form-control" name="number" placeholder="Password" required>
            </div>
            <div class="form-group col-lg-7  mb-2">
                <input type="submit" name="register" class="btn btn-dark btn-block py-2 font-weight-bold" value="Confirm" >
            </div>
        </form>
    </div> 
</div>
<?php
// $view= new view;
// $view->addPatient();
?>
</body>