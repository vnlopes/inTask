<?php
session_start();
require('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            header("Location: function/list.php");
            exit();
        } else {
            echo "Login inválido!";
        }
    } else {
        echo "Login inválido!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white flex justify-center items-center h-screen">
    <form method="POST" class="bg-gray-800 p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl mb-4 text-center">Login</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <p class="text-green-500 mb-4">Registro realizado com sucesso! Faça login.</p>
        <?php endif; ?>

        <input type="text" name="username" required placeholder="Usuário" class="mb-4 p-2 w-full rounded bg-gray-700 text-white placeholder-gray-400" />
        <input type="password" name="password" required placeholder="Senha" class="mb-4 p-2 w-full rounded bg-gray-700 text-white placeholder-gray-400" />
        <button type="submit" class="bg-blue-500 text-white py-2 rounded w-full">Entrar</button>
    </form>
</body>
</html>
