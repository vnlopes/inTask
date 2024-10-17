<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli('localhost', 'root', '', 'your_database_name');

// Função para salvar a tarefa no banco de dados em vez do Local Storage
function saveTask($user_id, $title, $content) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content, date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('iss', $user_id, $title, $content);
    $stmt->execute();
}

// Função para carregar as notas do banco de dados
function loadTasks($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, title, content, date FROM notes WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    return $tasks;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    saveTask($user_id, $title, $content);
}

$tasks = loadTasks($user_id);
?>

<!-- Aqui seu script de notas em JavaScript vai permanecer o mesmo, apenas mudando a fonte dos dados -->
