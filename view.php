<?php
    require_once 'source/template.php';
    require_once 'source/db(PDO).php';
    session_start();
    
    const LIMIT = 5;
    $sql = new DataBase();

    // Check Login status
    if (isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] === true)) {
        // Logout click
        if (isset($_POST['logout'])) {
            setcookie('remember', '', time() - 1209600);

            // Remove remember value
            $sql->removeRemember($_SESSION['id']);

            $_SESSION['loggedIn'] = false;
            session_destroy();
            unset($_SESSION);

            header('Location: index.php');
        }
        else {
            // Load avatar photo
            if (isset($_POST['load'])) {
                $file = 'images/' . $_FILES['userfile']['name'];

                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $file)) {
                    $_SESSION['avatar'] = $_FILES['userfile']['name'];
                    $sql->updateAvatar($_SESSION['id'], $_SESSION['avatar']);
                }
            }

            // Create a main page
            $page = new Template();
            $offset = $_GET['offset'] ?? 0;

            $page->setTemplate('templates/view.tpl');
            echo $page->getViewPage($offset, LIMIT);
        }
    }
    else {
        echo 'Error! You need to login...';
    }
