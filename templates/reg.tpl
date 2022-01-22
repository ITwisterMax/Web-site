<form method="post" action="index.php" name="regForm">
    <fieldset>
        <legend>Enter your information:</legend>
        {MESSAGE}<br>
        User name:<br>
        <input type="text" name="name"><br>
        E-mail:<br>
        <input type="email" name="email"><br>
        Login:<br>
        <input type="text" name="login"><br>
        Password:<br>
        <input type="password" name="password"><br>
        Enter math result:<br>
        <img src="templates/captcha.php"><input type="text" name="captcha"><br>
        <input type="submit" name="letReg" value="Register">
        <input type="submit" name="log" value="Login">
    </fieldset>
</form>
