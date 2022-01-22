<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="images/back.jpg" as="image">
    <link rel="stylesheet" href="styles/fileStyle.css">
    <title>Files</title>
</head>
<body>
    <div class="left">
        <h1>Post's {ID} files:</h1>
        <a href="view.php">Return</a><br><br>
        {MESSAGE}
        {FILES}
        <form method="POST" enctype="multipart/form-data" name="loadFile">
            <input type="hidden" name="MAX_FILE_SIZE" value="300000">
            <input name="userfile" type="file">
            <input type="submit" name="load" value="Load file">
        </form>
    </div>
    <div class="right">
        <img id="gif" src="images/animation1.gif">
    </div>
</body>
</html>
