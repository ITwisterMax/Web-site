<?php
    require_once 'source/login.php';
    session_start();

    $form = new LogOrReg();

    // Remember function
    if ($form->tryLogin()) {
        header('Location: view.php');
    }
    // Login or register page
    else {
        echo $form->getPage();
    }
