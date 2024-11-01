<?php
header('Content-Type: application/json');
$host = 'localhost';
$dbname = 'intask';
$user = 'root';
$pass = '';

$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Captura as ações e parâmetros
$action = $_GET['action'] ?? '';
$user_id = $_GET['user_id'] ?? 0;

// Ação para adicionar notas
if ($action == 'add') {
    $title = $_GET['title'];
    $content = $_GET['content'];
    $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content) VALUES (:user_id, :title, :content)");
    $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content]);
    echo json_encode(['status' => 'success', 'message' => 'Nota adicionada com sucesso.']);
    
// Ação para obter notas
} elseif ($action == 'get') {
    $stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retorna as notas como JSON
    echo json_encode($notes);
    
// Ação para atualizar notas
} elseif ($action == 'update') {
    $id = $_GET['id'];
    $title = $_GET['title'];
    $content = $_GET['content'];
    $stmt = $conn->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['title' => $title, 'content' => $content, 'id' => $id, 'user_id' => $user_id]);
    echo json_encode(['status' => 'success', 'message' => 'Nota atualizada com sucesso.']);
    
// Ação para deletar notas
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $id, 'user_id' => $user_id]);
    echo json_encode(['status' => 'success', 'message' => 'Nota deletada com sucesso.']);
} else {
    // Resposta para ação não reconhecida
    echo json_encode(['status' => 'error', 'message' => 'Ação não reconhecida.']);
}
?>
