<?php

// Подключение к базе данных

require_once 'db_connect.php';

?>

<!-- Ссылки на документы -->

<h2>Админ-панель:</h2>

<?php

// Функционал подтверждения или отказа администратором в админ-панели

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        // Подтверждение документа
        $query = "UPDATE documents SET approved = 1 WHERE id = $id";
        mysqli_query($conn, $query);
    } elseif ($action === 'reject') {
        // Отказ в документе
        $query = "UPDATE documents SET approved = 0 WHERE id = $id";
        mysqli_query($conn, $query);
    }
}


$query = "SELECT * FROM documents";
$result = mysqli_query($conn, $query);

// Закрытие соединения с базой данных

mysqli_close($conn);

echo '<ul>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<li><a href="' . $row['file_path'] . '">' . $row['name'] . '</a>';
    if ($row['approved'] == null) {
        echo ' - <a href="?id=' . $row['id'] . '&action=approve">Подтвердить</a>';
        echo ' - <a href="?id=' . $row['id'] . '&action=reject">Отказать</a>';
    }elseif($row['approved'] == 0) {
        echo ' - <a href="?id=' . $row['id'] . '&action=approve"></a> Отказано';
       }elseif($row['approved'] == 1) {
        echo ' - <a href="?id=' . $row['id'] . '&action=approve"></a> Подтверждено';
    }
    echo '</li>';
}
echo '</ul>';

?>

