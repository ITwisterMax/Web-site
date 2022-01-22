<?php
    require_once 'source/template.php';
    require_once 'source/db(PDO).php';
    session_start();

    $message = '';
    // Reset click
    if (isset($_POST['submit'])) {
        $login = (isset($_POST['login'])) ? $_POST['login'] : '';
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';

        $sql = new DataBase();

        // Update password and send it to user mail
        if ($sql->updatePassword($login, $email)) {
            $sub = "Password reset";
            $msg = "Your new $login account password:\n{$_SESSION['newPassword']}";
            $rec = $email;
            if (mail($rec, $sub, $msg)) {
                header('Location: index.php');
            }
            else {
                $message = '<font color="red">Error! Check your information...</font>';
            }
        }
        else {
            $message = '<font color="red">Error! Check your information...</font>';
        }
    }
        
    // Create a reset page
    $page = new Template();
    $page->setTemplate('templates/reset.tpl');
    echo $page->getResetPage($message);
