<?php
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
};


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === "new") {
            $title = $conn->$_POST['title'];
            if (!empty($title)) {
                $conn->query("INSERT INTO todolist (title) VALUES ('$title')");
            }
        }

        if ($action === "delete") {
            $id = $_POST["id"];
            $conn->query("DELETE FROM todolist WHERE id= $id");
        }

        if ($action === "toggle") {
            $id = $_POST["id"];
            $conn->query("UPDATE todolist SET done = 1 - done WHERE id = $id");
        }
    }
}

$tasks = [];
$result = $conn->query("SELECT * FROM todolist order by created_at DESC");


while ($row = $result->fetch_assoc()) {
    $taches[] = $row;
}

?>

 
      
        

</div>

</body>

</html>






?>