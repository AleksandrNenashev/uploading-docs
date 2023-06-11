<?php

// Подключение к базе данных

require_once 'db_connect.php';

// Обработка загрузки документов

if (isset($_POST['submit'])) {
    $file = $_FILES['document'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];

    // Переместите загруженный файл в желаемую директорию на сервере

    $upload_dir = 'documents/';
    $target_file = $upload_dir . $file_name;
    move_uploaded_file($file_tmp, $target_file);

    // Проверка ошибок загрузки файла
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Ошибка: Произошла ошибка при загрузке файла. Код ошибки: " . $file['error'];
        exit;
    }

    // Проверка, существует ли уже файл с таким именем
    // if (file_exists($target_file)) {
    //     echo "Ошибка: Файл с таким именем уже существует.";
    //     exit;
    // }

    // Фиксация ссылки на документ в базе данных

    $query = "INSERT INTO documents (name, file_path) VALUES ('$file_name', '$target_file')";
    mysqli_query($conn, $query);

    // После успешной загрузки, перенаправление пользователя на главную страницу

    header('Location: index.php');

    exit;
}

// Функционал вывода списка документов

$query = "SELECT * FROM documents";
$result = mysqli_query($conn, $query);

// Закрытие соединения с базой данных

mysqli_close($conn);

echo '<h2>Список документов:</h2>';

echo '<ul>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<li><a href="' . $row['file_path'] . '">' . $row['name'] . '</a>';
    if($row['approved'] == null) {
        echo ' - <a href="?id=' . $row['id'] . '&action=approve"></a> Ждет подтверждения';
       }elseif($row['approved'] == 1) {
        echo ' - <a href="?id=' . $row['id'] . '&action=approve"></a> Подтверждено';
       }elseif($row['approved'] == 0) {
        echo ' - <a href="?id=' . $row['id'] . '&action=approve"></a> Отказано';
    }
    echo '</li>';
}
echo '</ul>';

?>

<!-- HTML форма для загрузки документов -->

<h2>Загрузить документ:</h2>

<form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
    <input type="file" name="document" id="documentInput" required>
    <input type="submit" name="submit" value="Загрузить" onclick="validateFile()">
</form>

<!-- JavaScript код для проверки загружаемого файла -->

<script>
    function validateFile() {
        var fileInput = document.getElementById('documentInput');
        var file = fileInput.files[0];

        var allowedTypes = ['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'];

        if (!allowedTypes.includes(file.type)) {
            alert("Ошибка: Недопустимый тип файла. Разрешены только PDF, DOC, JPG и PNG файлы.");
            event.preventDefault(); // Остановка отправки формы
        }
    }
</script>
