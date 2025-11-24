<?php

define('DB_USER', 'root');
define('DB_PASS', '1704');
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
            $title = trim($_POST['title'] ?? '');
            if ($title !== '') {
                $stmt = $conn->prepare("INSERT INTO todo (title) VALUES (?)");
                $stmt->bind_param("s", $title);
                $stmt->execute();
                $stmt->close();
            }
        }

        if ($action === "delete") {
            $id = $_POST["id"];
            $conn->query("DELETE FROM todo WHERE id= $id");
        }

        if ($action === "toggle") {
            $id = $_POST["id"];
            $conn->query("UPDATE todo SET done = 1 - done WHERE id = $id");
        }
    }
}

$tasks = [];
$result = $conn->query("SELECT * from todo");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Document</title>
</head>

<body class="bg-light row">
    <form class="container" method="post">
        <div class="bg-dark py-3 ">
            <h1 class="text-center text-white">To-Do List</h1>
        </div>

        <div class="d-flex justify-content-center flex-column mt-4">
            <input type="text" placeholder="Task Title.." class="form-control mt-4" name="title">
            <button class=" btn btn-primary mt-4" value="new" name="action">Add</button>
            <ul class="list-group mt-4">
                <?php foreach ($tasks as $t): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center <?php echo ((int)$t['done']) ? 'list-group-item-success' : ''; ?>">
                        <div>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline <?php echo ((int)$t['done']) ? 'text-decoration-line-through text-muted' : ''; ?>" value="toggle" name="action">
                                    <?php ($t['title']); ?>
                                </button>
                            </form>
                        </div>
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                            <button class="btn btn-danger btn-sm" value="delete" name="action">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>


            </ul>
    </form>



</body>

</html>