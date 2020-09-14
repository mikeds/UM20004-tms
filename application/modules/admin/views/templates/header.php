<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=(isset($title) ? $title : APPNAME)?></title>
    <!-- Required meta tags -->
    <link rel="icon" type="image/png" href="<?=base_url() . 'assets/favicon.ico'?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php
        foreach ($stylesheets as $stylesheet) {
            echo '<link rel="stylesheet" href="'.$stylesheet.'" />';
        }
        
    ?>
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex justify-content-center">
            <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
                <a class="navbar-brand brand-logo" href="<?=base_url()?>"><?=APPNAME?></a>
                <a class="navbar-brand brand-logo-mini" href="#"><img src="<?=base_url()?>assets/images/bp_logo_mini.png" alt="logo"/></a>
                <!-- <a class="navbar-brand brand-logo" href="#"><img src="images/logo.svg" alt="logo"/></a>
                <a class="navbar-brand brand-logo-mini" href="#"><img src="images/logo-mini.svg" alt="logo"/></a> -->
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-sort-variant"></span>
                </button>
            </div>  
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <a id="portal-title" href="<?=base_url()?>"><?=APPNAME?></a>
            <div class="navbar-nav-center" id="logo"></div>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <span class="nav-profile-name"><?=isset($profile_name) ? $profile_name : ""?></span>
                        <img src="<?=base_url()?>assets/images/user-default.png" alt="profile"/>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <!-- <a class="dropdown-item">
                        <i class="mdi mdi-settings text-primary"></i>
                        Settings
                    </a> -->
                    <a class="dropdown-item" href="<?=isset($logout_url) ? $logout_url : "#"?>">
                        <i class="mdi mdi-logout text-primary"></i>
                        Logout
                    </a>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <?=isset($nav_sidebar_menu) ? $nav_sidebar_menu : ""?>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <?=isset($breadcrumbs_page) ? $breadcrumbs_page : ""?>
