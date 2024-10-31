<?php
session_start();
require_once 'db_connect.php'; // Arquivo para conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redireciona para a página de login
    exit();
}

$user_id = $_SESSION['user_id'];

// Função para salvar nota no banco de dados
function salvarNota($user_id, $conteudo) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notas (usuario_id, conteudo) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $conteudo);
    return $stmt->execute();
}

// Função para recuperar notas do usuário logado
function recuperarNotas($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM notas WHERE usuario_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Função para atualizar uma nota específica
function atualizarNota($nota_id, $conteudo, $user_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE notas SET conteudo = ? WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("sii", $conteudo, $nota_id, $user_id);
    return $stmt->execute();
}

// Função para apagar uma nota específica
function apagarNota($nota_id, $user_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM notas WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $nota_id, $user_id);
    return $stmt->execute();
}
?>
