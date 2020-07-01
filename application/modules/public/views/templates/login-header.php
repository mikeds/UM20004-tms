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
        <div class="container-fluid page-body-wrapper full-page-wrapper">