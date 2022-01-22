<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="images/back.jpg" as="image">
    <link rel="stylesheet" href="styles/postsStyle.css">
    <title>Posts list</title>
</head>
<body>
    <div class="left-block">
        <h1>Posts list:</h1>
        {NAVIGATION}
        <ol class="post">
            {ELEMENTS}
        </ol>
        {NAVIGATION}
        <form method="POST" action="edit.php"">
            <input type="submit" name="newPost" value="Create a new post">
        </form>
    </div>
    <div class="right-block">
        <div class="left-container">
            <img src="images/{IMAGE}">
            <form method="POST" enctype="multipart/form-data" action="view.php" name="loadForm">
                <input type="hidden" name="MAX_FILE_SIZE" value="300000">
                <input name="userfile" type="file" accept=".jpg, .jpeg, .png, .bmp">
                <input type="submit" name="load">
            </form>
        </div>        
        <div class="right-container">
            <label>Welcome back, {NAME}!</label>
            <form method="POST" action="view.php" name="logoutForm">
                <input type="submit" name="logout" value="Logout">
            </form>
        </div>
    </div>
    <img id="gif" src="images/animation1.gif">
</body>
</html>
