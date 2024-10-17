<?php
session_start();
require('db.php'); // Inclui a conexão com o banco de dados

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php");
    exit();
} else {
    echo "Erro ao excluir usuário: " . $stmt->error;
}

$stmt->close();
$conn->close(); // Fecha a conexão
?>
