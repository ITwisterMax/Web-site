<?php
    require_once 'source/template.php';
    session_start();

    if (isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] === true)) {
        $sql = new DataBase();
        
        if (isset($_POST['newPost']) || isset($_POST['createPost'])) {
            // Create a new post and return to the homepage
            if (isset($_POST['createPost'])) {
                $sql->addNewPost($_POST);

                unset($_POST);
                echo '<script>location.replace("view.php");</script>';
            }
            
            // Create a create page
            $page = new Template();
            $page->setTemplate('templates/create.tpl');
            echo $page->getEditOrCreatePage();
        }
        elseif (isset($_GET['id'])){
            // Update a specifical post and return to the homepage
            if (isset($_POST['updatePost'])) {
                $_POST['id'] = $_GET['id'];
                $sql->updatePost($_POST);

                unset($_POST);
                echo '<script>location.replace("view.php");</script>';
            }
            // Delete a specifical post and return to the homepage
            elseif (isset($_POST['deletePost'])) {
                $files = new Files($_GET['id']);
                $files->deleteDir();

                $sql->deletePost($_GET['id']);

                unset($_POST);
                echo '<script>location.replace("view.php");</script>';
            }

            // Create an edit page
            $page = new Template();
            $page->setTemplate('templates/edit.tpl');
            echo $page->getEditOrCreatePage($_GET['id'], true);
        }
        else {
            // Create a create page
            $page = new Template();
            $page->setTemplate('templates/create.tpl');
            echo $page->getEditOrCreatePage();
        }
    }
    else {
        echo 'Error! You need to login...';
    }
