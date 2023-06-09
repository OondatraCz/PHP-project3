<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['login'])){
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])){

        $host = "127.0.0.1";
        $db = "ip_3";
        $user = "www-aplikace";
        $pass = "Bezpe4n0Heslo.";
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);

        $employee_id = $_SESSION['id'];
        $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);

        $stmt = $pdo->prepare("UPDATE employee SET `password` = :new_password WHERE employee_id = :employee_id");
        $stmt->execute(['new_password' => $new_password, 'employee_id' => $employee_id]);

        header("Location: logout.php");
    }
    else{
        echo('<form method="POST" action="changePass.php">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <button type="submit">Submit</button>
      </form>');
    }
}
else{
    echo("Nejste přihlášeni");
}
