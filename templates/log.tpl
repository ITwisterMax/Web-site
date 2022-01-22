<form method="post" action="index.php" name="logForm">
    <fieldset>
        <legend>Enter your login and password:</legend>
        {MESSAGE}<br>
        Login:<br>
        <input type="text" name="login"><br>
        Password:<br>
        <input type="password" name="password"><br>
        Remember me:
        <input type="checkbox" name="rememberMe">
        <a href="reset.php">Forgot password?</a><br>
        <input type="submit" name="letLog" value="Login">
        <input type="submit" name="reg" value="Register">
    </fieldset>
</form>
