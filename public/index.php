<?php
session_start();

if (!isset($_SESSION['id']) && !isset($_SESSION['login'])) {

?>

<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
</head>
<body>
<form action="login.php" method="post">
    <h2>LOGIN</h2>
    <?php if (isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <label>User Name</label>
    <input type="text" name="uname" placeholder="User Name"><br>

    <label>User Name</label>
    <input type="password" name="password" placeholder="Password"><br>

    <button type="submit">Login</button>
</form>
</body>
</html>
<?php
}else{
     header("Location: home.php");
     exit();
}
 ?>