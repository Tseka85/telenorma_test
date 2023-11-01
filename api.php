<?php
$envFilePath = '.env';

if (file_exists($envFilePath)) {
    $envFileContents = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($envFileContents as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
} else {
    echo "Файл .env не найден.";
}

$dbName = getenv('DB_DATABASE');
$dbUser = getenv('DB_USERNAME');
$dbPassword = getenv('DB_PASSWORD');

$mysqli = new mysqli('db', $dbUser, $dbPassword, $dbName);

if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

$createTableSQL = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL
)";

if ($mysqli->query($createTableSQL) !== true) {
    echo "Ошибка при создании таблицы: " . $mysqli->error;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'add') {
    $name = $mysqli->real_escape_string($_POST['name']);
    $surname = $mysqli->real_escape_string($_POST['surname']);
    $position = $mysqli->real_escape_string($_POST['position']);
    
    $sql = "INSERT INTO users (name, surname, position) VALUES ('$name', '$surname', '$position')";
    
    if ($mysqli->query($sql) === true) {
        echo "Пользователь успешно добавлен.";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $mysqli->error;
    }
} elseif ($action == 'edit') {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';
    $newName = $mysqli->real_escape_string($_POST['name']);
    $newSurname = $mysqli->real_escape_string($_POST['surname']);
    $newPosition = $mysqli->real_escape_string($_POST['position']);
    
    $sql = "UPDATE users SET name='$newName', surname='$newSurname', position='$newPosition' WHERE id='$userId'";
    
    if ($mysqli->query($sql) === true) {
        echo "Пользователь успешно отредактирован.";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $mysqli->error;
    }
} elseif ($action == 'delete') {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';
    
    $sql = "DELETE FROM users WHERE id='$userId'";
    
    if ($mysqli->query($sql) === true) {
        echo "Пользователь успешно удален.";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $mysqli->error;
    }
} elseif ($action == 'getUser') {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';
    
    $sql = "SELECT * FROM users WHERE id='$userId'";
    
    $result = $mysqli->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    }
} elseif ($action == 'get') {
    $sql = "SELECT * FROM users";
    $result = $mysqli->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['surname'] . "</td>";
            echo "<td>" . $row['position'] . "</td>";
            echo "<td><button class='btn btn-primary edit-user' data-id='{$row['id']}'>Редактировать</button> <button class='btn btn-danger delete-user' data-id='{$row['id']}'>Удалить</button></td>";
            echo "</tr>";
        }
    } else {
        echo "Нет пользователей в базе данных.";
    }
    $mysqli->close();
} elseif ($action == 'execute_query') {
    $sql = "
            SELECT
                g.name AS product_name,
                af1.name AS field1_name,
                afv1.name AS field1_value,
                af2.name AS field2_name,
                afv2.name AS field2_value
            FROM
                goods AS g
            LEFT JOIN
                additional_goods_field_values AS agfv1 ON g.id = agfv1.good_id
            LEFT JOIN
                additional_field_values AS afv1 ON agfv1.additional_field_value_id = afv1.id
            LEFT JOIN
                additional_fields AS af1 ON afv1.additional_field_id = af1.id
            LEFT JOIN
                additional_goods_field_values AS agfv2 ON g.id = agfv2.good_id
            LEFT JOIN
                additional_field_values AS afv2 ON agfv2.additional_field_value_id = afv2.id
            LEFT JOIN
                additional_fields AS af2 ON afv2.additional_field_id = af2.id";
    
    $result = $mysqli->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo "Ошибка выполнения запроса: " . $mysqli->error;
    }
    $mysqli->close();
}
?>
