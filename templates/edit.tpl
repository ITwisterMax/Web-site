<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="images/back.jpg" as="image">
    <link rel="stylesheet" href="styles/editStyle.css">
    <title>Edit post</title>
</head>
<body>
    <h1>Edit post:</h1>
    <form name="edit" method="POST">
        <a href="view.php">Return</a><br><br>
        <label>Title:</label><br>
        <input type="text" name="title" value="{TITLE}" required><br>
        <label>Description:</label><br>
        <textarea name="description" required>{DESCRIPTION}</textarea><br>
        <label>Author:</label><br>
        <input type="text" name="author" value="{AUTHOR}" required><br>
        <label>Category:</label><br>
        <select name="category">{OPTIONS}</select><br>
        <input type="submit" name="updatePost" value="Update post">
        <input type="submit" name="deletePost" value="Delete post">
    </form>
    <img id="gif" src="images/animation1.gif">
</body>
</html>
