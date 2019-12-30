<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <title><?= (!empty($title) ? $title : '') ?></title>

    <!-- Application stylesheet -->
    <link rel="stylesheet" type="text/css" href="/public/css/app.css" media="screen"/>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/98f93d3825.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="custom-menu">
            <button type="button" id="sidebarCollapse">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="img bg-wrap text-center py-4" style="background-image: url(/public/images/background_1.jpg);">
            <div class="user-logo">
                <div class="img" style="background-image: url(/public/images/logo.jpg);"></div>
                <h3>Marco van de Lindt</h3>
            </div>
        </div>
        <ul class="list-unstyled components mb-5">
            <li class="active">
                <a href="/"><span class="fa fa-home mr-3"></span> Home</a>
            </li>
            <li>
                <a href="/tracks"><span class="fa fa-music mr-3"></span> Music</a>
            </li>
            <li>
                <a href="#"><span class="fab fa-playstation mr-3"></span> Playstation</a>
            </li>
            <li>
                <a href="#"><span class="fab fa-twitter mr-3"></span> Twitter</a>
            </li>
            <li>
                <a href="#"><span class="fab fa-facebook mr-3"></span> Facebook</a>
            </li>
            <li>
                <a href="#"><span class="fab fa-instagram mr-3"></span> Instagram</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content  -->
    <div id="content">
        <div class="header">

        </div>
