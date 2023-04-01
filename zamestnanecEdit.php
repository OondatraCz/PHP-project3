<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h1>Editovat informace o zaměstnanci</h1>

<?php
require_once("connectToDB.php");

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT, ["options" => ["min_range"=> 1]]);

if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
} else if(isset($_POST['submit'])){

    $conn = mysqli_connect($host, $username, $password, $database);
    $stmt = $pdo->prepare("UPDATE employee SET name=" . $_POST['name'] . ", surname=" . $_POST['surname'] . ", job=" . $_POST['job'] . ", wage=" . $_POST['wage'] . ", room=" . $_POST['room'] . " WHERE id=" . $id);

    // Update employee with id=1
    $sql = "UPDATE employee SET name='$name', surname='$surname', job='$job', wage=$wage, room=$room WHERE id=1";

    if (mysqli_query($conn, $sql)) {
        echo "Employee updated successfully";
    } else {
        echo "Error updating employee: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    
}
?>

<form method="POST" action="">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name"><br>

    <label for="surname">Surname:</label>
    <input type="text" name="surname" id="surname"><br>

    <label for="job">Job:</label>
    <input type="text" name="job" id="job"><br>

    <label for="wage">Wage:</label>
    <input type="text" name="wage" id="wage"><br>

    <label for="room">Room:</label>
    <input type="text" name="room" id="room"><br>

    <input type="submit" name="submit" value="Update Employee">
</form>

</body>
</html>
</body>
</html>