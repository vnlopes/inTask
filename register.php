<?php
require 'db.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password']; // Lembre-se de hash a senha em produção

    // Cria uma query para inserir os dados
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash a senha para segurança
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        // Redirecionar após o registro
        header('Location: login.php');
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }

    // Fecha a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>
