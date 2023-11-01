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

// SQL-запрос для получения данных
$query = "
    
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

$result = $mysqli->query($query);

echo '<table border="1">';
echo '<tr>';
echo '<th>Имя товара</th>';
echo '<th>Название допполя1</th>';
echo '<th>Значение допполя1</th>';
echo '<th>Название допполя2</th>';
echo '<th>Значение допполя2</th>';
echo '</tr>';

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['product_name'] . '</td>';
        echo '<td>' . $row['field1_name'] . '</td>';
        echo '<td>' . $row['field1_value'] . '</td>';
        echo '<td>' . $row['field2_name'] . '</td>';
        echo '<td>' . $row['field2_value'] . '</td>';
        echo '</tr>';
    }
} else {
    echo "Ошибка выполнения запроса: " . $mysqli->error;
}

echo '</table>';

// Закрытие соединения
$mysqli->close();

?>
