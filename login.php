<?php
require 'db.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica se o usuário existe
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifica a senha
        if (password_verify($password, $user['password'])) {
            // Redireciona para a página de criação de notas
            header('Location: /function/list.php');
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }

    // Fecha a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>

<form action="login.php" method="POST" class="bg-gray-800 p-6 rounded-lg">
    <h2 class="text-white text-2xl mb-4">Login</h2>
    <input type="text" name="username" placeholder="Nome de Usuário" required class="mb-4 p-2 w-full rounded">
    <input type="password" name="password" placeholder="Senha" required class="mb-4 p-2 w-full rounded">
    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Entrar</button>
</form>
