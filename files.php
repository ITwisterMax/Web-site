<?php
    require_once 'source/template.php';
    require_once 'source/loadFiles.php';
    session_start();

    // Check Login status
    if (isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] === true)) {
        $message = '';

        // Load new file
        if (isset($_POST['load']) && $_FILES['userfile']['name'] !== '') {
            $files = new Files($_GET['id']);
            $message = $files->loadFile();
        }

        // Create a files page
        $page = new Template();
        $page->setTemplate('templates/files.tpl');
        echo $page->getFilesPage($_GET['id'], $message);        
    }
    else {
        echo 'Error! You need to login...';
    }
