<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preload" href="images/back.jpg" as="image">
  <link rel="stylesheet" href="styles/formStyle.css">
  <title>Reset password</title>
</head>
<body>
    <form method="POST" action="reset.php" name='resetForm'>
        <fieldset>
            <legend>Enter your information:</legend>
            <a href="index.php">Return...</a><br>
            {MESSAGE}<br>
            Login:<br>
            <input type="text" name="login" required><br>
            E-mail:<br>
            <input type="email" name="email" required><br>
            <input type="submit" name="submit" value="Send a new password">
        </fieldset>
    </form>
    <img id="gif" src="images/animation1.gif">
</body>
</html>
